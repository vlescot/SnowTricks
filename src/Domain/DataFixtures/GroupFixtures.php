<?php

namespace App\Domain\DataFixtures;


use App\Domain\DTO\GroupDTO;
use App\Domain\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class GroupFixtures extends Fixture
{
    private function getData($file)
    {
        $fixturesPath = __DIR__ . '/Fixtures/';
        $fixtures = Yaml::parse(file_get_contents( $fixturesPath . $file .'.yaml', true));
        return $fixtures;
    }

    public function load(ObjectManager $manager)
    {
        $groups = $this->getData('Group');

        foreach ($groups as $group){
            $groupDTO = new GroupDTO();
            $groupDTO->setName($group['name']);

            $group = new Group();
            $group->creation($groupDTO);

            $manager->persist($group);
        }

        $manager->flush();
    }
}