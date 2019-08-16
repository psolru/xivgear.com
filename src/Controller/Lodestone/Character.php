<?php

namespace App\Controller\Lodestone;

use App\Services\Lodestone\CharacterService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @return Response
     * @throws Exception
     * @Route("/Character/{lodestone_id}", host="api.%base_host%}", name="api_lodestone_character")
     * @Route("/character/{lodestone_id}", host="api.%base_host%")
     */
    public function api_index($lodestone_id, Request $request)
    {
        $job = $request->get('job');
        $role = $request->get('role');

        $character = $this->service->get($lodestone_id);

        $data = [];
        $data['character'] = [
            'lodestone_id' => $character->getLodestoneId(),
            'name' => $character->getName(),
            'server' => $character->getServer(),
            'avatarUrl' => $character->getAvatarUrl(),
            'portraitUrl' => $character->getPortraitUrl(),
            'updated_at' => $character->getUpdatedAt(),
            'created_at' => $character->getCreatedAt()
        ];

        $data['jobs'] = [];
        foreach ($character->getLodestoneClassMappings() as $lodestoneClass) {
            if ($job != null)
                if ($job != strtolower($lodestoneClass->getShort()))
                    continue;

            if ($role != null)
                if ($role != $lodestoneClass->getLodestoneClass()->getType())
                    continue;

            $data['jobs'][strtolower($lodestoneClass->getShort())] = [
                'level' => $lodestoneClass->getLevel(),
                'experience' => $lodestoneClass->getExperience(),
                'experience_total' => $lodestoneClass->getExperienceTotal(),
                'stats' => null
            ];
        }

        foreach ($character->getGearSets() as $gearSet) {

            if ($job != null)
                if ($job != strtolower($gearSet->getLodestoneClass()->getShortEn()))
                    continue;

            if ($role != null)
                if ($role != $gearSet->getLodestoneClass()->getType())
                    continue;

            $data['jobs'][strtolower($gearSet->getLodestoneClass()->getShortEn())]['stats'] = [
                'iLevel' => $gearSet->getILevel(),
                'dexterity' => $gearSet->getAttribute('dexterity'),
                'vitality' => $gearSet->getAttribute('vitality'),
                'intelligence' => $gearSet->getAttribute('intelligence'),
                'mind' => $gearSet->getAttribute('mind'),
                'criticalHit' => $gearSet->getAttribute('criticalHit'),
                'determination' => $gearSet->getAttribute('determination'),
                'directHitRate' => $gearSet->getAttribute('directHitRate'),
                'defense' => $gearSet->getAttribute('defense'),
                'magicDefense' => $gearSet->getAttribute('magicDefense'),
                'attackPower' => $gearSet->getAttribute('attackPower'),
                'skillSpeed' => $gearSet->getAttribute('skillSpeed'),
                'attackMagicPotency' => $gearSet->getAttribute('attackMagicPotency'),
                'healingMagicPotency' => $gearSet->getAttribute('healingMagicPotency'),
                'spellSpeed' => $gearSet->getAttribute('spellSpeed'),
                'tenacity' => $gearSet->getAttribute('tenacity'),
                'piety' => $gearSet->getAttribute('piety'),
                'gathering' => $gearSet->getAttribute('gathering'),
                'perception' => $gearSet->getAttribute('perception'),
                'craftsmanship' => $gearSet->getAttribute('craftsmanship'),
                'control' => $gearSet->getAttribute('control'),
                'cP' => $gearSet->getAttribute('cP'),
                'gP' => $gearSet->getAttribute('gP'),
                'hP' => $gearSet->getAttribute('hP'),
                'mP' => $gearSet->getAttribute('mP'),
                'tP' => $gearSet->getAttribute('tP')
            ];
        }

        return $this->json($data);
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

        if ($character->isJustCreated()) {
            return $this->redirectToRoute('lodestone_character', ['lodestone_id' => $lodestone_id]);
        }

       return $this->render('lodestone_character/index.html.twig', [
            'controller_name' => 'Character',
            'character' => $character,
            'showCreationHint' => strtotime('+10 seconds', $character->getUpdatedAt()->getTimestamp()) >= strtotime('now') ? true : false
        ]);
    }
}
