<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedAtColumnInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedAtTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedTimestampInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedTimestampTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\EntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\GeneratedIdTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedAtColumnInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedAtTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedTimestampInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedTimestampTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidColumnInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidTrait;
use Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Repository\ArtistRepository;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
class Artist
    implements EntityInterface, UuidColumnInterface, CreatedAtColumnInterface, UpdatedAtColumnInterface
{
    use GeneratedIdTrait;
    use UuidTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

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
