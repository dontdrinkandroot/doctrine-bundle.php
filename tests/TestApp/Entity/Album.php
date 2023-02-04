<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\DoctrineBundle\Entity\EntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\GeneratedIdEntityTrait;

#[ORM\Entity]
class Album implements EntityInterface
{
    use GeneratedIdEntityTrait;

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
