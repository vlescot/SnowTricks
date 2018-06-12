<?php

namespace App\Domain\DataFixtures;

use App\Domain\DTO\UserDTO;
use App\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class UserFixtures extends Fixture
{
    private function getData($file)
    {
        $fixturesPath = __DIR__ . '/Fixtures/';
        $fixtures = Yaml::parse(file_get_contents( $fixturesPath . $file .'.yaml', true));
        return $fixtures;
    }

    public function load(ObjectManager $manager)
    {
        $users = $this->getData('User');

        foreach ($users as $key => $user){
            $userName = $user['Username'];

            $userDTO = new UserDTO();
            $userDTO->setUsername($user['Username']);
            $userDTO->setPassword($user['Password']);
            $userDTO->setEmail($user['Email']);
            $userDTO->setToken($user['Token']);
            $userDTO->setRoles($user['Roles']);

            $user = new User();
            $user->registration($userDTO);

            $this->addReference($userName, $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
}