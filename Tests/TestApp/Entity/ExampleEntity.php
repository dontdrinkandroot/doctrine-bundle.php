<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedTimestampEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\DefaultUuidEntity;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedTimestampEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\ExampleEntityRepository;
use Dontdrinkandroot\DoctrineBundle\Type\MillisecondsType;

#[ORM\Entity(repositoryClass: ExampleEntityRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ExampleEntity extends DefaultUuidEntity
    implements CreatedEntityInterface, UpdatedEntityInterface, CreatedTimestampEntityInterface,
               UpdatedTimestampEntityInterface
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $updated = null;

    #[ORM\Column(type: MillisecondsType::NAME)]
    private ?int $createdTimestamp = null;

    #[ORM\Column(type: MillisecondsType::NAME, nullable: true)]
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
    public function setCreated(?DateTimeInterface $created): static
    {
        $this->created = $created;
        return $this;
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
    public function setUpdated(?DateTimeInterface $updated): static
    {
        $this->updated = $updated;
        return $this;
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
    public function setCreatedTimestamp(?int $timestamp): static
    {
        $this->createdTimestamp = $timestamp;
        return $this;
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
    public function setUpdatedTimestamp(?int $timestamp): static
    {
        $this->updatedTimestamp = $timestamp;
        return $this;
    }
}
