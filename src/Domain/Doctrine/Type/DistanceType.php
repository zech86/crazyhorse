<?php

namespace App\Domain\Doctrine\Type;

use App\Domain\Distance;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\FloatType;

final class DistanceType extends FloatType
{
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Distance((float) $value);
    }

    /**
     * @param Distance $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->value();
    }

    public function getName()
    {
        return 'distance';
    }
}