<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedAtColumnInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedAtColumnTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedDatetimeInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedDatetimeTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\EntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\GeneratedIdColumnTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedAtColumnInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedAtColumnTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedDatetimeInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedDatetimeTrait;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\GenreRepository;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre implements EntityInterface, CreatedAtColumnInterface, UpdatedAtColumnInterface
{
    use GeneratedIdColumnTrait;
    use CreatedAtColumnTrait;
    use UpdatedAtColumnTrait;

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
