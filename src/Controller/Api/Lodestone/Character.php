<?php

namespace App\Controller\Api\Lodestone;

use App\Controller\Api\ApiHelpers;
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
        $extended = $request->get('extended');

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
                'gear' => null,
                'attributes' => null
            ];
        }

        foreach ($character->getGearSets() as $gearSet) {

            if ($job != null)
                if ($job != strtolower($gearSet->getLodestoneClass()->getShortEn()))
                    continue;

            if ($role != null)
                if ($role != $gearSet->getLodestoneClass()->getType())
                    continue;

            $data['jobs'][strtolower($gearSet->getLodestoneClass()->getShortEn())]['gear'] = [
                'iLevel' => $gearSet->getILevel(),
                'mainHand' => ApiHelpers::convertGearSetItemToArray(
                    $gearSet->getGearsetItemBySlot('mainHand'), $extended ?: false
                ),
                'head' => ApiHelpers::convertGearSetItemToArray(
                    $gearSet->getGearsetItemBySlot('head'), $extended ?: false
                ),
                'body' => ApiHelpers::convertGearSetItemToArray(
                    $gearSet->getGearsetItemBySlot('body'), $extended ?: false
                ),
                'hands' => ApiHelpers::convertGearSetItemToArray(
                    $gearSet->getGearsetItemBySlot('hands'), $extended ?: false
                ),
                'waist' => ApiHelpers::convertGearSetItemToArray(
                    $gearSet->getGearsetItemBySlot('waist'), $extended ?: false
                ),
                'legs' => ApiHelpers::convertGearSetItemToArray(
                    $gearSet->getGearsetItemBySlot('legs'), $extended ?: false
                ),
                'feet' => ApiHelpers::convertGearSetItemToArray(
                    $gearSet->getGearsetItemBySlot('feet'), $extended ?: false
                ),
                'earrings' => ApiHelpers::convertGearSetItemToArray(
                    $gearSet->getGearsetItemBySlot('earrings'), $extended ?: false
                ),
                'necklace' => ApiHelpers::convertGearSetItemToArray(
                    $gearSet->getGearsetItemBySlot('necklace'), $extended ?: false
                ),
                'bracelets' => ApiHelpers::convertGearSetItemToArray(
                    $gearSet->getGearsetItemBySlot('bracelets'), $extended ?: false
                ),
                'ring1' => ApiHelpers::convertGearSetItemToArray(
                    $gearSet->getGearsetItemBySlot('ring1'), $extended ?: false
                ),
                'ring2' => ApiHelpers::convertGearSetItemToArray(
                    $gearSet->getGearsetItemBySlot('ring2'), $extended ?: false
                ),
                'soulCrystal' => ApiHelpers::convertGearSetItemToArray(
                    $gearSet->getGearsetItemBySlot('soulCrystal'), $extended ?: false
                ),
            ];

            $data['jobs'][strtolower($gearSet->getLodestoneClass()->getShortEn())]['attributes'] = [
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
        }

        return $this->json($data);
    }
}
