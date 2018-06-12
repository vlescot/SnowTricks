<?php

namespace App\Domain\DataFixtures;


use App\Domain\DTO\TrickDTO;
use App\Domain\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class TrickFixtures extends Fixture
{
    private function getData($file)
    {
        $fixturesPath = __DIR__ . '/Fixtures/';
        $fixtures = Yaml::parse(file_get_contents( $fixturesPath . $file .'.yaml', true));
        return $fixtures;
    }

    public function load(ObjectManager $manager)
    {
        $tricks = $this->getData('Trick');

        foreach ($tricks as $trick){
            $trickDTO = new TrickDTO();
            $trickDTO->setTitle($trick['Title']);
            $trickDTO->setSlug($trick['Slug']);
            $trickDTO->setDescription($trick['Description']);
            $trickDTO->setAuthor($this->getReference($trick['Author']));

            $trick = new Trick();
            $trick->creation($trickDTO);

            $manager->persist($trick);
        }

        $manager->flush();
    }
}