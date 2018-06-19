<?php

namespace App\Domain\DataFixtures;

use App\Domain\DTO\GroupDTO;
use App\Domain\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GroupFixtures extends Fixture implements OrderedFixtureInterface
{
    private $fixturesHelper;

    public function __construct(FixturesHelper $fixturesHelper)
    {
        $this->fixturesHelper = $fixturesHelper;
    }

    public function load(ObjectManager $manager)
    {
        $groups = $this->fixturesHelper->get('Group');

        foreach ($groups as $key => $group) {
            $groupDTO = new GroupDTO();
            $groupDTO->setName($group['name']);

            if (isset($group['Reference'])) {
                foreach ($group['Reference'] as $referenceEntity => $ref) {
                    $callableMethod = $this->fixturesHelper->getCallable($referenceEntity, $groupDTO);

                    foreach ($ref as $referenceName) {
                        $groupDTO->$callableMethod($this->getReference($referenceName));
                    }
                }
            }

            $group = new Group();
            $group->creation($groupDTO);

            $manager->persist($group);

            $this->addReference($key, $group);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}
