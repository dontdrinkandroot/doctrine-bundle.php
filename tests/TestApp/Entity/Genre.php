<?php

namespace Dontdrinkandroot\DoctrineBundle\Tests\TestApp\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Dontdrinkandroot\DoctrineBundle\Entity\EntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\GeneratedIdEntityTrait;

#[ORM\Entity]
class Genre implements EntityInterface
{
    use GeneratedIdEntityTrait;

    /** @var Collection<array-key,Artist> */
    #[ORM\ManyToMany(targetEntity: Artist::class, mappedBy: 'genres')]
    public Collection $artists;

    public function __construct(
        #[ORM\Column(type: 'string', length: 255, nullable: false)]
        public string $name,
    ) {
    }
}
