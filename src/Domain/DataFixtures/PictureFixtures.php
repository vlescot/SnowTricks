<?php

namespace App\Domain\DataFixtures;

use App\Domain\DTO\PictureDTO;
use App\Domain\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PictureFixtures extends Fixture implements OrderedFixtureInterface
{
    private $fixturesHelper;

    public function __construct(FixturesHelper $fixturesHelper)
    {
        $this->fixturesHelper = $fixturesHelper;
    }

    public function load(ObjectManager $manager)
    {
        $pictures = $this->fixturesHelper->get('Picture');

        foreach ($pictures as $key => $picture) {
            $pictureDTO = new PictureDTO();
            $pictureDTO->setPath($picture['path']);
            $pictureDTO->setFileName($picture['filename']);
            $pictureDTO->setAlt($picture['alt']);

            $picture = new Picture();
            $picture->add($pictureDTO);

            $manager->persist($picture);

            $this->addReference($key, $picture);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
