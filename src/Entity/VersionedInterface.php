<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

interface VersionedInterface
{
    public int $version {
        get;
    }
}
