<?php

namespace App\Domain\DataFixtures;

use App\Domain\DTO\PictureDTO;
use App\Domain\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class PictureFixtures extends Fixture
{
    private function getData($file)
    {
        $fixturesPath = __DIR__ . '/Fixtures/';
        $fixtures = Yaml::parse(file_get_contents( $fixturesPath . $file .'.yaml', true));
        return $fixtures;
    }

    public function load(ObjectManager $manager)
    {
        $pictures = $this->getData('Picture');

        foreach ($pictures as $key => $picture){
            $pictureDTO = new PictureDTO();
            $pictureDTO->setPath($picture['path']);
            $pictureDTO->setFileName($picture['filename']);
            $pictureDTO->setAlt($picture['alt']);

            $picture = new Picture();
            $picture->add($pictureDTO);

            $manager->persist($picture);
        }

        $manager->flush();
    }
}