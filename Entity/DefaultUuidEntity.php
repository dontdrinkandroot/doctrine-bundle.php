<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\DoctrineBundle\Entity\DefaultEntity;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\MappedSuperclass()
 *
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultUuidEntity extends DefaultEntity implements UuidEntityInterface
{
    /**
     * @ORM\Column(type="uuid", nullable=false)
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
    public function setUuid(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }
}
