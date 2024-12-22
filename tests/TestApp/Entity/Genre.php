<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedAtInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedAtTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedDatetimeInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedDatetimeTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\EntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\GeneratedIdTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedAtInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedAtTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedDatetimeInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedDatetimeTrait;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\GenreRepository;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre implements EntityInterface, CreatedAtInterface, UpdatedAtInterface
{
    use GeneratedIdTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    /** @var Collection<array-key,Artist> */
    #[ORM\ManyToMany(targetEntity: Artist::class, mappedBy: 'genres')]
    public Collection $artists;

    public function __construct(
        #[ORM\Column(type: 'string', length: 255, nullable: false)]
        public string $name,
    ) {
        $this->artists = new ArrayCollection();
    }
}
