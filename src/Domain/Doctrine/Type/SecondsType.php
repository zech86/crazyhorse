<?php

namespace App\Domain\Doctrine\Type;

use App\Domain\Seconds;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\FloatType;

final class SecondsType extends FloatType
{
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Seconds((float) $value);
    }

    /**
     * @param Seconds $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->value();
    }

    public function getName()
    {
        return 'seconds';
    }
}