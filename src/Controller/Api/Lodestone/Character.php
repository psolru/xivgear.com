<?php

namespace App\Controller\Api\Lodestone;

use App\Services\Lodestone\CharacterService;
use App\Services\Lodestone\ItemService;
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
     * @Route("/Character/{lodestone_id}", host="api.%base_host%", name="api_lodestone_character")
     * @Route("/character/{lodestone_id}", host="api.%base_host%")
     */
    public function api_index($lodestone_id, Request $request)
    {
        $job = $request->get('job');
        $role = $request->get('role');

        $character = $this->service->get($lodestone_id);

        $data = [];
        $data['character'] = [
            'lodestoneId' => $character->getLodestoneId(),
            'name' => $character->getName(),
            'server' => $character->getServer(),
            'avatarUrl' => $character->getAvatarUrl(),
            'portraitUrl' => $character->getPortraitUrl(),
            'updatedAt' => $character->getUpdatedAt(),
            'createdAt' => $character->getCreatedAt()
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
                'experienceTotal' => $lodestoneClass->getExperienceTotal(),
                'stats' => null,
                'gear' => null
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
                'criticalHitRate' => $gearSet->getAttribute('criticalHitRate'),
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

            $gear = [
                'mainHand' => (array) $gearSet->getGearsetItemBySlot('mainHand')
            ];
            dump($gear);
            die;

            $gear = [
                'iLevel' => $gearSet->getILevel(),
                'mainHand' => null,
                'head' => null,
                'body' => null,
                'hands' => null,
                'waist' => null,
                'legs' => null,
                'feet' => null,
                'earrings' => null,
                'necklace' => null,
                'bracelets' => null,
                'ring1' => null,
                'ring2' => null,
                'soulCrystal' => null
            ];
            foreach ($gearSet->getGearsetItems() as $gearsetItem)
            {
                $item = ItemService::get($gearsetItem->getItemId());
                $gear[$gearsetItem->getSlot()] = [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'iconUrl' => $item->getIconUrl(),
                    'levelItem' => $item->getLevelItem(),
                    'levelEquip' => $item->getLevelEquip(),
                    'materia' => null
                ];

                $materiaList = [];
                foreach ($gearsetItem->getMateria() as $materiaItem)
                {
                    $materiaList[] = [
                        'id' => $materiaItem->getId(),
                        'name' => $materiaItem->getName(),
                        'iconUrl' => $materiaItem->getIconUrl(),
                        'levelItem' => $materiaItem->getLevelItem(),
                        'levelEquip' => $materiaItem->getLevelEquip()
                    ];
                }
                $gear[$gearsetItem->getSlot()]['materia'] = $materiaList;
            }
            $data['jobs'][strtolower($gearSet->getLodestoneClass()->getShortEn())]['gear'] = $gear;
        }

        return $this->json($data);
    }
}
