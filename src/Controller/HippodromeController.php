<?php

namespace App\Controller;

use App\Domain\Hippodrome\Hippodrome;
use App\Entity\Race;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HippodromeController extends AbstractController
{
    /**
     * @Route(name="hippodrome_index", path="/")
     */
    public function indexAction()
    {
        return new Response('');
    }

    /**
     * @Route("hippodrome/start_new_race", name="hippodrome_start_new_race")
     */
    public function startNewRaceAction()
    {
        $hippodrome = $this->container->get(Hippodrome::class);
        $hippodrome->startNewRace();

        //return $this->redirectToRoute('hippodrome_index');
    }

    /**
     * @Route("hippodrome/advance_race/{race}", name="hippodrome_advance_race")
     */
    public function advanceRaceAction(Race $race)
    {
        $hippodrome = $this->container->get(Hippodrome::class);
        $hippodrome->advanceRace($race);

        //return $this->redirectToRoute('hippodrome_index');
    }

    /**
     * @Route("hippodrome/state", name="hippodrome_state")
     */
    public function stateAction()
    {
        $hippodrome = $this->container->get(Hippodrome::class);
        $state = $hippodrome->stateAsArray();

        return new JsonResponse($state);
    }

    public static function getSubscribedServices()
    {
        return parent::getSubscribedServices() + [
            Hippodrome::class => Hippodrome::class
        ];
    }
}