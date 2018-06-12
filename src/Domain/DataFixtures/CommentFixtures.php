<?php

namespace App\Domain\DataFixtures;


use App\Domain\DTO\CommentDTO;
use App\Domain\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    private function getData($file)
    {
        $fixturesPath = __DIR__ . '/Fixtures/';
        $fixtures = Yaml::parse(file_get_contents( $fixturesPath . $file .'.yaml', true));
        return $fixtures;
    }

    public function load(ObjectManager $manager)
    {
        $comments = $this->getData('Comment');

        foreach ($comments as $comment){
            $commentDTO = new CommentDTO();
            $commentDTO->setAuthor($this->getReference($comment['Author']));
            $commentDTO->setContent($comment['Content']);
            $commentDTO->setValidated($comment['Validated']);

            $comment = new Comment();
            $comment->add($commentDTO);

            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}