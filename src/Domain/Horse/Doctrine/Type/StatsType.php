<?php

namespace App\Domain\Horse\Doctrine\Type;

use App\Domain\Horse\Stats;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;

final class StatsType extends TextType
{
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $data = json_decode($value, true);

        return Stats::fromArray($data);
    }

    /**
     * @param Stats $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return json_encode($value->toArray());
    }

    public function getName()
    {
        return 'distance';
    }
}