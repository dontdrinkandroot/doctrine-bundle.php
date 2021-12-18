<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
class Entity implements EntityInterface
{
    use GeneratedIdEntityTrait;

    /**
     * Checks if this entity represents the same entity as another one. Usually this is checked via the class and id.
     *
     * @param mixed $other
     *
     * @return bool
     */
    public function equals($other): bool
    {
        if (!is_object($other)) {
            return false;
        }

        $thisClass = ClassUtils::getRealClass(get_class($this));
        $otherClass = ClassUtils::getRealClass(get_class($other));

        if ($thisClass !== $otherClass) {
            return false;
        }

        /** @var Entity $otherEntity */
        $otherEntity = $other;

        return $this->getId() === $otherEntity->getId();
    }
}
