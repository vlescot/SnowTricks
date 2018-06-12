<?php

namespace App\Domain\DataFixtures;


use App\Domain\DTO\VideoDTO;
use App\Domain\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class VideoFixtures extends Fixture
{
    private function getData($file)
    {
        $fixturesPath = __DIR__ . '/Fixtures/';
        $fixtures = Yaml::parse(file_get_contents( $fixturesPath . $file .'.yaml', true));
        return $fixtures;
    }

    public function load(ObjectManager $manager)
    {
        $videos = $this->getData('Video');

        foreach ($videos as $key => $video){
            $videoDTO = new VideoDTO();
            $videoDTO->setUrl($video['Url']);
            $videoDTO->setPlatform($video['Platform']);

            $video = new Video();
            $video->add($videoDTO);

            $manager->persist($video);
        }

        $manager->flush();
    }
}
