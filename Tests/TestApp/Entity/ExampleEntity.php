<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedTimestampEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\DefaultUuidEntity;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedTimestampEntityInterface;

/**
 * @ORM\Entity()
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class ExampleEntity extends DefaultUuidEntity
    implements CreatedEntityInterface, UpdatedEntityInterface, CreatedTimestampEntityInterface, UpdatedTimestampEntityInterface
{
    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $created = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $updated = null;

    /**
     * @ORM\Column(type="milliseconds")
     */
    private ?int $createdTimestamp = null;

    /**
     * @ORM\Column(type="milliseconds")
     */
    private ?int $updatedTimestamp = null;

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreated(?DateTimeInterface $created): void
    {
        $this->created = $created;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdated(): ?DateTimeInterface
    {
        return $this->updated;
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdated(?DateTimeInterface $updated): void
    {
        $this->updated = $updated;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedTimestamp(): ?int
    {
        return $this->createdTimestamp;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedTimestamp(?int $createdTimestamp): void
    {
        $this->createdTimestamp = $createdTimestamp;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedTimestamp(): ?int
    {
        return $this->updatedTimestamp;
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedTimestamp(?int $updatedTimestamp): void
    {
        $this->updatedTimestamp = $updatedTimestamp;
    }
}
