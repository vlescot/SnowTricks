<?php
declare(strict_types = 1);

namespace App\Domain\DataFixtures;

use App\Domain\Entity\Comment;
use App\Domain\Entity\Group;
use App\Domain\Entity\Picture;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use App\Domain\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class AppFixtures
 * @package App\Domain\DataFixtures
 */
final class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $password;

    /**
     * AppFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $password
     */
    public function __construct(UserPasswordEncoderInterface $password)
    {
        $this->password = $password;
    }

    /**
     * @param string $entityName
     *
     * @return array
     */
    private function getDataFixture(string $entityName) :array
    {
        return Yaml::parse(file_get_contents(__DIR__.'/Fixtures/'. $entityName .'.yaml', true));
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $pictures = $this->getDataFixture('Picture');
        $users = $this->getDataFixture('User');
        $tricks = $this->getDataFixture('Trick');
        $videos = $this->getDataFixture('Video');
        $comments = $this->getDataFixture('Comment');
        $groups = $this->getDataFixture('Group');


        foreach ($pictures as $name => $picture) {
            $picture = new Picture(
                    $picture['path'],
                    $picture['filename'],
                    $picture['alt'] . '-image'
                );

            $this->addReference($name, $picture);
            $manager->persist($picture);
        }


        foreach ($users as $name => $user) {
            $userPicture = $this->getReference($user['Reference']['Picture']);

            $userEntity = new User();
            $userEntity->registration(
                $user['Username'],
                $user['Email'],
                $this->password->encodePassword($userEntity, $user['Password']),
                $userPicture
            );

            $userEntity->setConfirmation(true);

            $manager->persist($picture);
            $manager->persist($userEntity);

            $this->addReference($name, $userEntity);
        }


        foreach ($groups as $name => $group) {
            $group = new Group(
                $group['Name']
            );

            $this->addReference($name, $group);
        }


        foreach ($tricks as $name => $trick) {
            $author = $this->getReference($trick['Reference']['Author']);
            $mainPicture = $this->getReference($trick['Reference']['MainPicture']);

            $pictures = [];
            foreach ($trick['Reference']['Picture'] as $pictureReference) {
                $pictures[] = $this->getReference($pictureReference);
            }
            $videoEntities = [];
            foreach ($trick['Reference']['Video'] as $videoReference) {
                $videoEntities[] = new Video($videos[$videoReference]['iFrame']);
            }

            $groups = new ArrayCollection();
            foreach ($trick['Reference']['Group'] as $groupReference) {
                $groups->add($this->getReference($groupReference));
            }

            $trickEntity = new Trick(
                $trick['Title'],
                $trick['Description'],
                $author,
                $mainPicture,
                $pictures,
                $videoEntities,
                $groups
            );

            $manager->persist($trickEntity);

            $this->addReference($name, $trickEntity);
        }


        foreach ($comments as $comment) {
            $author = $this->getReference($comment['Reference']['Author']);
            $trick = $this->getReference($comment['Reference']['Trick']);

            $comment = new Comment(
                $comment['Content'],
                $author,
                $trick
            );

            $manager->persist($comment);
        }

        $manager->flush();
        echo "\nFixtures Loaded !\n";
    }
}
