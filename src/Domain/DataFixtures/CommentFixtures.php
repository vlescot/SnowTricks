<?php

namespace App\Domain\DataFixtures;

use App\Domain\DTO\CommentDTO;
use App\Domain\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements OrderedFixtureInterface
{
    private $fixturesHelper;

    public function __construct(FixturesHelper $fixturesHelper)
    {
        $this->fixturesHelper = $fixturesHelper;
    }

    public function load(ObjectManager $manager)
    {
        $comments = $this->fixturesHelper->get('Comment');

        foreach ($comments as $key => $comment) {
            $commentDTO = new CommentDTO();
            $commentDTO->setContent($comment['Content']);
            $commentDTO->setValidated($comment['Validated']);

            if (isset($comment['Reference'])) {
                foreach ($comment['Reference'] as $referenceEntity => $ref) {
                    $callableMethod = $this->fixturesHelper->getCallable($referenceEntity, $commentDTO);

                    foreach ($ref as $referenceName) {
                        $commentDTO->$callableMethod($this->getReference($referenceName));
                    }
                }
            }

            $comment = new Comment();
            $comment->add($commentDTO);

            $manager->persist($comment);

            $this->addReference($key, $comment);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}
