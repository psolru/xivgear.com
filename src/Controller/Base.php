<?php

namespace App\Controller;

use App\Repository\GearSetRepository;
use App\Repository\Lodestone\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Base extends AbstractController
{
    /**
     * @Route("/", name="api_home", host="api.%base_host%")
     * @return Response
     */
    public function api_index() {
        return $this->render('default/api_index.html.twig');
    }

    /**
     * @Route("/", name="home")
     * @param CharacterRepository $lodestoneCharacterRepository
     * @param GearSetRepository $gearSetRepository
     * @return Response
     */
    public function index(
        CharacterRepository $lodestoneCharacterRepository,
        GearSetRepository $gearSetRepository
    ) {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'recentlyAdded' => $lodestoneCharacterRepository->getRecentlyAdded(10),
            'recentlyUpdated' => $lodestoneCharacterRepository->getRecentlyUpdated(10),
            'characterCount' => count($lodestoneCharacterRepository->findAllExistingOnes()),
            'gearSetCount' => count($gearSetRepository->findAll())
        ]);
    }

    /**
     * @Route("/sitemap.xml", name="sitemap.xml.twig")
     * @param CharacterRepository $lodestoneCharacterRepository
     * @return Response
     */
    public function sitemap(CharacterRepository $lodestoneCharacterRepository)
    {
        $response = $this->render('default/sitemap.xml.twig', [
            'characterList' => $lodestoneCharacterRepository->findAllExistingOnes()
        ]);
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }
}
