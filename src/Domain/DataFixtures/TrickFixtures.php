<?php

namespace App\Domain\DataFixtures;

use App\Domain\DTO\TrickDTO;
use App\Domain\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TrickFixtures extends Fixture implements OrderedFixtureInterface
{
    private $fixturesHelper;

    public function __construct(FixturesHelper $fixturesHelper)
    {
        $this->fixturesHelper = $fixturesHelper;
    }

    public function load(ObjectManager $manager)
    {
        $tricks = $this->fixturesHelper->get('Trick');

        foreach ($tricks as $key => $trick) {
            $trickDTO = new TrickDTO();
            $trickDTO->setTitle($trick['Title']);
            $trickDTO->setSlug($trick['Slug']);
            $trickDTO->setDescription($trick['Description']);
            $trickDTO->setValidated($trick['Validated']);

            if (isset($trick['Reference'])) {
                foreach ($trick['Reference'] as $referenceEntity => $ref) {
                    $callableMethod = $this->fixturesHelper->getCallable($referenceEntity, $trickDTO);

                    foreach ($ref as $referenceName) {
                        $trickDTO->$callableMethod($this->getReference($referenceName));
                    }
                }
            }

            $trick = new Trick();
            $trick->creation($trickDTO);

            $manager->persist($trick);

            $this->addReference($key, $trick);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}
