<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedTimestampTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\Entity;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedTimestampEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedTimestampTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidTrait;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\ExampleEntityRepository;

#[ORM\Entity(repositoryClass: ExampleEntityRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ExampleEntity extends Entity implements UuidEntityInterface
{
    use UuidTrait;
    use CreatedTimestampTrait;
    use UpdatedTimestampTrait;

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}
