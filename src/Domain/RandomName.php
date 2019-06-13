<?php

namespace App\Domain;

final class RandomName
{
    public static function create(): string
    {
        $names = [
            'Atik', 'Atlas', 'Auva', 'Avior', 'Azelfafage', 'Azha', 'Azmidiske',
            'Baham', 'Becrux', 'Beid', 'Botein', 'Brachium', 'Caph', 'Cebalrai',
            'Celaeno', 'Chara', 'Chort', 'Cursa', 'Dabih'
        ];

        $surnames = [
            'Lesath', 'Maasym', 'Maia', 'Ruchba', 'Ruchbah', 'Rukbat', 'Matar',
            'Mebsuta', 'Meissa', 'Mekbuda', 'Menkalinan', 'Menkar', 'Menkent',
            'Menkib', 'Yildun', 'Zaniah', 'Zaurak', 'Zavijah', 'Zibal', 'Zosma'
        ];

        $name = $names[mt_rand(0,count($names) - 1)];
        $name.= ' ';
        $name.= $surnames[mt_rand(0,count($surnames) - 1)];

        return $name;
    }
}