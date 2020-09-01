<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="bigint", nullable=false, options={"unsigned"=true})
     */
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @internal IDs are autogenerated. Use only in tests.
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
