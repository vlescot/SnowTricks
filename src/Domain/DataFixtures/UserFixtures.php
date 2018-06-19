<?php

namespace App\Domain\DataFixtures;

use App\Domain\DTO\UserDTO;
use App\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    /**
     * @var \App\Domain\DataFixtures\FixturesHelper
     */
    private $fixturesHelper;

    public function __construct(FixturesHelper $fixturesHelper)
    {
        $this->fixturesHelper = $fixturesHelper;
    }

    public function load(ObjectManager $manager)
    {
        $users = $this->fixturesHelper->get('User');

        foreach ($users as $key => $user) {
            $userDTO = new UserDTO();
            $userDTO->setUsername($user['Username']);
            $userDTO->setPassword($user['Password']);
            $userDTO->setValidated($user['Validated']);
            $userDTO->setEmail($user['Email']);
            $userDTO->setToken($user['Token']);
            $userDTO->setRoles($user['Roles']);
            $userDTO->setEnabled($user['Enabled']);

            if (isset($user['Reference'])) {
                foreach ($user['Reference'] as $referenceEntity => $ref) {
                    $callableMethod = $this->fixturesHelper->getCallable($referenceEntity, $userDTO);

                    foreach ($ref as $referenceName) {
                        $userDTO->$callableMethod($this->getReference($referenceName));
                    }
                }
            }

            $user = new User();
            $user->registration($userDTO);

            $manager->persist($user);

            $this->addReference($key, $user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
