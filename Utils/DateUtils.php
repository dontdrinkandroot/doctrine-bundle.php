<?php

namespace Dontdrinkandroot\DoctrineBundle\Utils;

use DateTimeInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DateUtils
{
    public static function getCurrentTimeInMilliseconds(): int
    {
        return (int)(microtime(true) * 1000);
    }

    public static function toMilliseconds(DateTimeInterface $dateTime)
    {
        return (int)(((float)$dateTime->format('U.u')) * 1000);
    }
}
