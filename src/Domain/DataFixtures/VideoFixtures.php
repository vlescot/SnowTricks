<?php

namespace App\Domain\DataFixtures;

use App\Domain\DTO\VideoDTO;
use App\Domain\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class VideoFixtures extends Fixture implements OrderedFixtureInterface
{
    private $fixturesHelper;

    public function __construct(FixturesHelper $fixturesHelper)
    {
        $this->fixturesHelper = $fixturesHelper;
    }

    public function load(ObjectManager $manager)
    {
        $videos = $this->fixturesHelper->get('Video');

        foreach ($videos as $key => $video) {
            $videoDTO = new VideoDTO();
            $videoDTO->setUrl($video['Url']);
            $videoDTO->setPlatform($video['Platform']);

            $video = new Video();
            $video->add($videoDTO);

            $manager->persist($video);

            $this->addReference($key, $video);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
