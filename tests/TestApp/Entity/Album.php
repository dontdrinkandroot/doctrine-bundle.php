<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\CreatedTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\EntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\GeneratedIdTrait;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UpdatedTrait;

#[ORM\Entity]
class Album implements EntityInterface, CreatedInterface, UpdatedInterface
{
    use GeneratedIdTrait;
    use CreatedTrait;
    use UpdatedTrait;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Artist::class, inversedBy: 'albums')]
        #[ORM\JoinColumn(nullable: false)]
        public Artist $artist,

        #[ORM\Column(type: 'string', length: 255, nullable: false)]
        public string $title,

        #[ORM\OneToOne(targetEntity: Album::class, inversedBy: 'nextAlbum')]
        #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
        public ?Album $previousAlbum = null,

        #[ORM\OneToOne(targetEntity: Album::class, mappedBy: 'previousAlbum')]
        public ?Album $nextAlbum = null
    ) {
    }
}
