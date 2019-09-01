<?php


namespace App\Services\Lodestone;

use App\Entity\Lodestone\Character;
use App\Services\AbstractService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use App\Services\Importer\Lodestone\Character as CharacterImporter;

class CharacterService extends AbstractService
{
    /**
     * @var CharacterImporter
     */
    private $importer;

    /**
     * CharacterService constructor.
     * @param EntityManagerInterface $em
     * @param CharacterImporter $importer
     */
    public function __construct(EntityManagerInterface $em, CharacterImporter $importer)
    {
        parent::__construct($em);
        $this->importer = $importer;
    }

    /**
     * @param $lodestone_id
     * @return Character
     * @throws Exception
     */
    public function get($lodestone_id): Character
    {
        $character = $this->em->getRepository(Character::class)->findOneBy(['lodestone_id' => $lodestone_id]);
        if (!$character) {
            $character = $this->importer->import($lodestone_id);
            $character->setJustCreated(true);
        }
        return $character;
    }
}