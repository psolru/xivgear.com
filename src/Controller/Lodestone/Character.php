<?php

namespace App\Controller\Lodestone;

use App\Services\Lodestone\CharacterService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Character extends AbstractController
{
    /**
     * @var CharacterService
     */
    private $service;

    /**
     * Character constructor.
     * @param CharacterService $lodestoneCharacterService
     */
    public function __construct(CharacterService $lodestoneCharacterService)
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
            'controller_name' => 'Character',
            'character' => $character
        ]);
    }
}
