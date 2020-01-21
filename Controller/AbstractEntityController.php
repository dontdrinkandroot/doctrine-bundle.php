<?php

namespace Dontdrinkandroot\DoctrineBundle\Controller;

use DateTime;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Dontdrinkandroot\Entity\EntityInterface;
use Dontdrinkandroot\Entity\UpdatedEntityInterface;
use Dontdrinkandroot\Entity\UuidEntityInterface;
use Dontdrinkandroot\Pagination\Pagination;
use Dontdrinkandroot\Utils\ClassNameUtils;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use function is_object;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Environment;

abstract class AbstractEntityController implements EntityControllerInterface
{
    protected $routePrefix = null;

    protected $viewPrefix = null;

    protected $pathPrefix = null;

    /** @var Environment */
    private $twig;

    /** @var ManagerRegistry */
    private $registry;

    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var RouterInterface */
    private $router;

    /** @var PropertyAccessorInterface */
    protected $propertyAccessor;

    public function __construct(
        ManagerRegistry $registry,
        AuthorizationCheckerInterface $authorizationChecker,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        Environment $twig,
        PropertyAccessorInterface $propertyAccessor
    ) {
        $this->twig = $twig;
        $this->registry = $registry;
        $this->authorizationChecker = $authorizationChecker;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * {@inheritdoc}
     */
    public function listAction(Request $request): Response
    {
        $this->checkListActionAuthorization($request);

        $view = $this->getListTemplate();
        $model = $this->getListModel($request);

        return $this->render($view, $model);
    }

    /**
     * {@inheritdoc}
     */
    public function detailAction(Request $request, $id): Response
    {
        $entity = $this->fetchEntity($id);
        $this->checkDetailActionAuthorization($request, $entity);

        $response = new Response();
        $lastModified = $this->getLastModified($entity);
        if (null !== $lastModified) {

            $response->setLastModified($lastModified);
            $response->setPublic();

            if ($response->isNotModified($request)) {
                return $response;
            }
        }

        $model = $this->getDetailModel($request, $entity);
        $view = $this->getDetailTemplate();

        return $this->render($view, $model, $response);
    }

    /**
     * {@inheritdoc}
     */
    public function editAction(Request $request, $id = null): Response
    {
        $new = true;
        $entity = null;
        if (null !== $id && $id !== 'new') {
            $new = false;
            $entity = $this->fetchEntity($id);
        }

        if ($new) {
            $this->checkCreateActionAuthorization($request);
        } else {
            $this->checkEditActionAuthorization($request, $entity);
        }
        $form = $this->createForm($this->getFormType(), $entity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $this->postProcessSubmittedEntity($request, $entity);
            $objectManager = $this->getEntityManager();
            if ($new) {
                $objectManager->persist($entity);
            }
            $objectManager->flush($entity);

            return $this->createPostEditResponse($request, $entity);
        }

        $view = $this->getEditTemplate();

        return $this->render(
            $view,
            [
                'entity'     => $entity,
                'idProperty' => $this->getIdProperty(),
                'form'       => $form->createView()
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function deleteAction(Request $request, $id): Response
    {
        $entity = $this->fetchEntity($id);
        $this->checkDeleteActionAuthorization($request, $entity);

        $objectManager = $this->getEntityManager();
        $objectManager->remove($entity);
        $objectManager->flush();

        return $this->createPostDeleteResponse($request, $entity);
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutePrefix(): string
    {
        if (null !== $this->routePrefix) {
            return $this->routePrefix;
        }

        $entityShortName = ClassNameUtils::getShortName($this->getEntityClass());

        return 'entity.' . $entityShortName;
    }

    /**
     * {@inheritdoc}
     */
    public function getPathPrefix(): string
    {
        if (null !== $this->pathPrefix) {
            return $this->pathPrefix;
        }

        $entityShortName = ClassNameUtils::getShortName($this->getEntityClass());

        return '/' . $entityShortName . 's/';
    }

    public function setRoutePrefix(?string $routePrefix)
    {
        $this->routePrefix = $routePrefix;
    }

    public function setViewPrefix(?string $viewPrefix)
    {
        $this->viewPrefix = $viewPrefix;
    }

    public function setPathPrefix(?string $pathPrefix)
    {
        $this->pathPrefix = $pathPrefix;
    }

    protected function getListTemplate(): string
    {
        return $this->getViewPrefix() . '/list.html.twig';
    }

    protected function getListModel(Request $request): array
    {
        $page = $this->getPage($request);
        $perPage = $this->getPerPage($request);
        $paginator = $this->findPaginated($page, $perPage);

        return [
            'page'        => $page,
            'perPage'     => $perPage,
            'entities'    => $paginator,
            'title'       => $this->getListTitle(),
            'fields'      => $this->getListFields(),
            'routes'      => $this->getRoutes(),
            'entityClass' => $this->getEntityClass(),
            'idProperty'  => $this->getIdProperty()
        ];
    }

    protected function getDetailTemplate(): string
    {
        return $this->getViewPrefix() . '/detail.html.twig';
    }

    protected function getDetailModel(Request $request, $entity): array
    {
        return [
            'entity'      => $entity,
            'title'       => $this->getDetailTitle($entity),
            'routes'      => $this->getRoutes(),
            'fields'      => $this->getDetailFields(),
            'entityClass' => $this->getEntityClass(),
            'idProperty'  => $this->getIdProperty()
        ];
    }

    protected function getEditTemplate(): string
    {
        return $this->getViewPrefix() . '/edit.html.twig';
    }

    protected function fetchEntity($id)
    {
        $entity = $this->getDoctrine()->getRepository($this->getEntityClass())->find($id);
        if (null === $entity) {
            throw new NotFoundHttpException();
        }

        return $entity;
    }

    protected function getRoutes(): array
    {
        return [
            'list'   => $this->getListRoute(),
            'detail' => $this->getDetailRoute(),
            'edit'   => $this->getEditRoute(),
            'delete' => $this->getDeleteRoute()
        ];
    }

    protected function getListRoute(): string
    {
        return $this->getRoutePrefix() . ".list";
    }

    protected function getDetailRoute(): string
    {
        return $this->getRoutePrefix() . ".detail";
    }

    protected function getEditRoute(): string
    {
        return $this->getRoutePrefix() . ".edit";
    }

    protected function getDeleteRoute(): string
    {
        return $this->getRoutePrefix() . ".delete";
    }

    protected function getViewPrefix(): string
    {
        if (null !== $this->viewPrefix) {
            return $this->viewPrefix;
        }

        return '@DdrDoctrine/Entity';
    }

    /**
     * @return array
     */
    protected function getListFields()
    {
        return [
            'id' => 'Id'
        ];
    }

    /**
     * @return array
     */
    protected function getDetailFields()
    {
        return [
            'id' => 'Id'
        ];
    }

    protected function createPostEditResponse(Request $request, $entity): Response
    {
        return $this->redirectToRoute(
            $this->getDetailRoute(),
            ['id' => $this->propertyAccessor->getValue($entity, $this->getIdProperty())]
        );
    }

    protected function createPostDeleteResponse(Request $request, $entity): Response
    {
        return $this->redirectToRoute($this->getListRoute());
    }

    protected function getLastModified($entity): ?DateTime
    {
        if (is_a($entity, UpdatedEntityInterface::class)) {
            /** @var UpdatedEntityInterface $updatedEntity */
            $updatedEntity = $entity;

            return $updatedEntity->getUpdated();
        }

        return null;
    }

    protected function checkListActionAuthorization(Request $request)
    {
        if (!$this->isGranted(CrudAction::READ, $this->getEntityClass())) {
            throw new AccessDeniedException();
        }
    }

    protected function checkDetailActionAuthorization(Request $request, $entity)
    {
        if (!$this->isGranted(CrudAction::READ, $entity)) {
            throw new AccessDeniedException();
        }
    }

    protected function checkCreateActionAuthorization(Request $request)
    {
        if (!$this->isGranted(CrudAction::CREATE, $this->getEntityClass())) {
            throw new AccessDeniedException();
        }
    }

    protected function checkEditActionAuthorization(Request $request, $entity)
    {
        if (!$this->isGranted(CrudAction::UPDATE, $entity)) {
            throw new AccessDeniedException();
        }
    }

    protected function checkDeleteActionAuthorization(Request $request, $entity)
    {
        if (!$this->isGranted(CrudAction::DELETE, $entity)) {
            throw new AccessDeniedException();
        }
    }

    protected function getPerPage(Request $request): int
    {
        return $request->query->get('perpage', 10);
    }

    protected function getPage(Request $request): int
    {
        return $request->query->get('page', 1);
    }

    protected function postProcessSubmittedEntity(Request $request, $entity)
    {
        /*Hook*/
    }

    protected abstract function getEntityClass(): string;

    protected abstract function getFormType(): string;

    protected function render(string $name, array $context, ?Response $response = null): Response
    {
        $content = $this->twig->render($name, $context);
        if (null === $response) {
            $response = new Response();
        }
        $response->setContent($content);

        return $response;
    }

    protected function getDoctrine(): ManagerRegistry
    {
        return $this->registry;
    }

    protected function isGranted($attributes, $subject = null): bool
    {
        return $this->authorizationChecker->isGranted($attributes, $subject);
    }

    protected function createForm(string $type, $data = null, array $options = []): FormInterface
    {
        return $this->formFactory->create($type, $data, $options);
    }

    protected function findPaginated(int $page, int $perPage): Paginator
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
            ->select('entity')
            ->from($this->getEntityClass(), 'entity');

        $queryBuilder->setFirstResult(($page - 1) * $perPage);
        $queryBuilder->setMaxResults($perPage);

        return new Paginator($queryBuilder);
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        $objectManager = $this->getDoctrine()->getManagerForClass($this->getEntityClass());
        if (!$objectManager instanceof EntityManagerInterface) {
            throw new RuntimeException('ObjectManager is not an EntityManager');
        }

        return $objectManager;
    }

    protected function redirectToRoute(string $route, array $parameters = [], int $status = 302): RedirectResponse
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    protected function redirect(string $url, int $status = 302): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }

    protected function generateUrl(
        string $route,
        array $parameters = [],
        int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    ): string {
        return $this->router->generate($route, $parameters, $referenceType);
    }

    protected function getListTitle(): string
    {
        return 'List';
    }

    protected function getDetailTitle(object $entity): string
    {
        return $this->propertyAccessor->getValue($entity, $this->getIdProperty());
    }

    protected function getIdProperty()
    {
        return 'id';
    }
}
