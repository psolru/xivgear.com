<?php

namespace App\Controller\Metrics;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \App\Services\Metrics\Character as CharacterService;

class Character extends AbstractController
{
    /**
     * @Route("/metrics/character", name="metrics_character")
     * @param CharacterService $characterService
     * @return Response
     */
    public function index(CharacterService $characterService)
    {
        
        return $this->render('metrics/index.html.twig', [
            'data' => [
                0 => 12,
                1 => 8,
                2 => 6,
                3 => 4,
                4 => 3,
                5 => 9,
                6 => 118,
                7 => 17,
                8 => 8,
                9 => 73,
                10 => 4,
                11 => 7,
                12 => 13,
                13 => 9,
                14 => 18,
                15 => 23,
                16 => 13,
                17 => 21,
                18 => 18,
                19 => 12,
                20 => 52,
                21 => 10,
                22 => 10,
                23 => 19,
            ]
        ]);
    }
}
