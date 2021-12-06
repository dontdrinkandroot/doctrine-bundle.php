<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\MappedSuperclass()
 */
class DefaultUuidEntity extends DefaultEntity implements UuidEntityInterface
{
    /**
     * @ORM\Column(type="uuid", nullable=false, unique=true)
     */
    protected ?UuidInterface $uuid = null;

    /**
     * {@inheritdoc}
     */
    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    /**
     * {@inheritdoc}
     */
    public function setUuid(UuidInterface $uuid): static
    {
        $this->uuid = $uuid;
        return $this;
    }
}
