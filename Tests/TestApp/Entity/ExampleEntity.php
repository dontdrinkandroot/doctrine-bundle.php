<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedTimestampEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedTimestampEntityTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\Entity;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedTimestampEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedTimestampEntityTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityTrait;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\ExampleEntityRepository;

#[ORM\Entity(repositoryClass: ExampleEntityRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ExampleEntity extends Entity
    implements UuidEntityInterface, CreatedTimestampEntityInterface, UpdatedTimestampEntityInterface
{
    use UuidEntityTrait;
    use CreatedTimestampEntityTrait;
    use UpdatedTimestampEntityTrait;

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}
