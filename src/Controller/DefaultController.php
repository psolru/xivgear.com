<?php

namespace App\Controller;

use App\Repository\GearSetRepository;
use App\Repository\LodestoneCharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param LodestoneCharacterRepository $lodestoneCharacterRepository
     * @param GearSetRepository $gearSetRepository
     * @return Response
     */
    public function index(
        LodestoneCharacterRepository $lodestoneCharacterRepository,
        GearSetRepository $gearSetRepository
    ) {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'recentlyAdded' => $lodestoneCharacterRepository->getRecentlyAdded(),
            'recentlyUpdated' => $lodestoneCharacterRepository->getRecentlyUpdated(),
            'characterCount' => count($lodestoneCharacterRepository->findAll()),
            'gearSetCount' => count($gearSetRepository->findAll())
        ]);
    }
}
