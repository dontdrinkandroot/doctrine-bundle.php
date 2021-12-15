<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\MappedSuperclass()
 */
class DefaultUuidEntity extends DefaultEntity implements UuidEntityInterface
{
    /**
     * @ORM\Column(type="uuid", nullable=false, unique=true)
     */
    protected ?Uuid $uuid = null;

    /**
     * {@inheritdoc}
     */
    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    /**
     * {@inheritdoc}
     */
    public function setUuid(Uuid $uuid): static
    {
        $this->uuid = $uuid;
        return $this;
    }
}
