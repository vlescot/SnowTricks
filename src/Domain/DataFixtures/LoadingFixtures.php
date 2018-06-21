<?php

namespace App\Domain\DataFixtures;

use App\Domain\DTO\CommentDTO;
use App\Domain\DTO\GroupDTO;
use App\Domain\DTO\PictureDTO;
use App\Domain\DTO\TrickDTO;
use App\Domain\DTO\UserDTO;
use App\Domain\DTO\VideoDTO;
use App\Domain\Entity\Comment;
use App\Domain\Entity\Group;
use App\Domain\Entity\Picture;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use App\Domain\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

/**
 * Class LoadingFixtures
 * @package App\Domain\DataFixtures
 */
class LoadingFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $users = $this->getDataFixture('User');
        $tricks = $this->getDataFixture('Trick');
        $pictures = $this->getDataFixture('Picture');
        $videos = $this->getDataFixture('Video');
        $comments = $this->getDataFixture('Comment');
        $groups = $this->getDataFixture('Group');


        foreach ($users as $key => $user) {
            $userPicture = $user['Reference']['Picture'][0];

            $pictureDTO = new PictureDTO(
                $pictures[$userPicture]['path'],
                $pictures[$userPicture]['filename'],
                $pictures[$userPicture]['alt']
            );

            $picture = new Picture();
            $picture->add($pictureDTO);

            $userDTO = new UserDTO(
                $user['Username'],
                $user['Password'],
                $user['Email'],
                $user['Token'],
                $user['Roles'],
                $user['Validated'],
                $user['Enabled'],
                $picture
            );

            $user = new User();
            $user->registration($userDTO);

            $manager->persist($picture);
            $manager->persist($user);

            $this->addReference($key, $user);
        }
        $manager->flush();
        echo "Users Loaded...\n";


        foreach ($tricks as $key => $trick) {
            $author = $this->getReference($trick['Reference']['Author'][0]);

            $trickPicture = $trick['Reference']['MainPicture'][0];

            $pictureDTO = new PictureDTO(
                $pictures[$trickPicture]['path'],
                $pictures[$trickPicture]['filename'],
                $pictures[$trickPicture]['alt']
            );

            $mainPicture = new Picture();
            $mainPicture->add($pictureDTO);

            $manager->persist($mainPicture);

            $trickDTO = new TrickDTO(
                $trick['Title'],
                $trick['Slug'],
                $trick['Description'],
                $trick['Validated'],
                $author,
                $mainPicture,
                null,
                null,
                null,
                null
            );

            $trick = new Trick();
            $trick->creation($trickDTO);

            $author->addTrick($trick);

            $manager->persist($trick);
            $manager->persist($author);

            $this->addReference($key, $trick);
        }
        $manager->flush();
        echo "Tricks Loaded...\n";


        foreach ($pictures as $key => $picture) {
            if (isset($picture['Reference']['Trick'])) {
                $trick = $this->getReference($picture['Reference']['Trick'][0]);
            } else {
                $trick = null;
            }

            $pictureDTO = new PictureDTO(
                $picture['path'],
                $picture['filename'],
                $picture['alt'],
                $trick
            );

            $picture = new Picture();
            $picture->add($pictureDTO);

            $manager->persist($picture);
        }
        echo "Pictures Loaded...\n";


        foreach ($comments as $comment) {
            $author = $this->getReference($comment['Reference']['Author'][0]);
            $trick = $this->getReference($comment['Reference']['Trick'][0]);

            $commentDTO = new CommentDTO(
                $comment['Content'],
                $comment['Validated'],
                $author,
                $trick
            );

            $comment = new Comment();
            $comment->add($commentDTO);

            $author->addComment($comment);
            $trick->addComment($comment);

            $manager->persist($comment);
            $manager->persist($author);
            $manager->persist($trick);
        }
        $manager->flush();
        echo "Comments Loaded...\n";


        foreach ($videos as $key => $video) {
            $trick = $this->getReference($video['Reference']['Trick'][0]);

            $videoDTO = new VideoDTO(
                $video['Url'],
                $video['Platform'],
                $trick
            );

            $video = new Video();
            $video->add($videoDTO);

            $manager->persist($video);

            $this->addReference($key, $video);
        }
        $manager->flush();
        echo "Videos Loaded...\n";


        foreach ($groups as $key => $group) {
            $trickCollection = new ArrayCollection();

            foreach ($group['Reference']['Trick'] as $ref) {
                $trickCollection->add($this->getReference($ref));
            }

            $groupDTO = new GroupDTO(
                $group['Name'],
                $trickCollection
            );

            $group = new Group();
            $group->create($groupDTO);

            $manager->persist($group);

            foreach ($trickCollection->getIterator() as $trick) {
                $trick->addGroup($group);
                $manager->persist($trick);
            }
        }
        $manager->flush();
        echo "Groups Loaded...\n\nDATABASE COMPLETED !\n";
    }

    private function getDataFixture(string $entityName)
    {
        return Yaml::parse(file_get_contents(__DIR__.'/Fixtures/' . $entityName . '.yaml', true));
    }
}
