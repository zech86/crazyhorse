<?php

namespace App\Entity;

use App\Domain\Horse\Stats;
use App\Domain\RandomName;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="horse")
 */
class Horse
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Jockey", cascade={"persist"}, fetch="EAGER")
     */
    private $jockey;

    /**
     * @ORM\Column(type="stats", nullable=false)
     */
    private $stats;

    public function __construct(Jockey $jockey, Stats $stats)
    {
        $this->name = RandomName::create();
        $this->jockey = $jockey;
        $this->stats = $stats;
    }

    public static function default()
    {
        return new self(new Jockey(), Stats::default());
    }

    public function name(): string
    {
        return $this->name;
    }

    public function jockey(): Jockey
    {
        return $this->jockey;
    }

    public function decreaseSpeed(): void
    {
        $this->stats = $this->stats->decreaseSpeed();
    }

    public function stats(): Stats
    {
        return $this->stats;
    }
}
