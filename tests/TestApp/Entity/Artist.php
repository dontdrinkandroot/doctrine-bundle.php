<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedTimestampEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedTimestampEntityTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\EntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\GeneratedIdEntityTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedTimestampEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedTimestampEntityTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityTrait;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\ArtistRepository;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
class Artist
    implements EntityInterface, UuidEntityInterface, CreatedTimestampEntityInterface, UpdatedTimestampEntityInterface
{
    use GeneratedIdEntityTrait;
    use UuidEntityTrait;
    use CreatedTimestampEntityTrait;
    use UpdatedTimestampEntityTrait;

    /** @var Collection<array-key,Genre> */
    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'artists')]
    public Collection $genres;

    /** @var Collection<array-key,Album> */
    #[ORM\OneToMany(targetEntity: Album::class, mappedBy: 'artists')]
    public Collection $albums;

    public function __construct(
        #[ORM\Column(type: 'string', length: 255, nullable: false)]
        public string $name,
    ) {
        $this->genres = new ArrayCollection();
        $this->albums = new ArrayCollection();
    }
}
