<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\Entity\EntityInterface;

/**
 * @ORM\MappedSuperclass()
 *
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", nullable=false)
     */
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Checks if this entity represents the same entity as another one. Usually this is checked via the class and id.
     *
     * @param mixed $other
     *
     * @return bool
     */
    public function equals($other): bool
    {
        if (null === $other || !is_object($other)) {
            return false;
        }

        $thisClass = ClassUtils::getRealClass(get_class($this));
        $otherClass = ClassUtils::getRealClass(get_class($other));

        if ($thisClass !== $otherClass) {
            return false;
        }

        /** @var DefaultEntity $otherEntity */
        $otherEntity = $other;

        return $this->getId() === $otherEntity->getId();
    }
}