<?php

namespace Dontdrinkandroot\DoctrineBundle\Entity;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
interface UpdatedTimestampEntityInterface
{
    public function getUpdatedTimestamp(): ?int;

    public function setUpdatedTimestamp(int $timestamp);
}
