<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="jockey")
 */
class Jockey
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")w
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
}