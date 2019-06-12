<?php

namespace App\Controller;

use App\Repository\GearSetRepository;
use App\Repository\LodestoneCharacterRepository;
use App\Services\LodestoneCharacterService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LodestoneCharacterController extends AbstractController
{
    /**
     * @var LodestoneCharacterService
     */
    private $service;

    /**
     * LodestoneCharacterController constructor.
     * @param LodestoneCharacterService $lodestoneCharacterService
     */
    public function __construct(LodestoneCharacterService $lodestoneCharacterService)
    {
        $this->service = $lodestoneCharacterService;
    }

    /**
     * @param $lodestone_id
     * @return Response
     * @throws Exception
     * @Route("/Character/{lodestone_id}", name="lodestone_character")
     * @Route("/character/{lodestone_id}")
     */
    public function index($lodestone_id)
    {
        $character = $this->service->get($lodestone_id);

        return $this->render('lodestone_character/index.html.twig', [
            'controller_name' => 'LodestoneCharacterController',
            'character' => $character
        ]);
    }
}
