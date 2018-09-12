<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\Service\CollectionManager\CollectionChecker;

use App\Domain\DTO\PictureDTO;
use App\Domain\DTO\VideoDTO;
use App\Domain\Entity\Picture;
use App\Domain\Entity\Video;
use App\Service\CollectionManager\CollectionUpdateChecker;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class CollectionUpdateCheckerTest extends KernelTestCase
{
    /**
     * @param array $collection
     * @param array $collectionDTO
     * @param int $newObjectExpectation
     * @param int $oldObjectExpectation
     *
     * @throws \ReflectionException
     *
     * @dataProvider provideData
     */
    public function testCompareProvideGoodValues(
        array $collection,
        array $collectionDTO,
        int $newObjectExpectation,
        int $oldObjectExpectation
    ) {
        $collectionChecker = new CollectionUpdateChecker();
        $collectionChecker->compare($collection, $collectionDTO);

        $newObjects = $collectionChecker->getNewObjects();
        $deletedObjects = $collectionChecker->getDeletedObjects();

        static::assertCount($newObjectExpectation, $newObjects, 'getNewObject');
        static::assertCount($oldObjectExpectation, $deletedObjects, 'getDeletedObject');
    }

    /**
     * @return \Generator
     *
     * @throws \Exception
     */
    public function provideData()
    {
        static::bootKernel();

        $publicFolder = $this::$kernel->getRootDir() . './../public/';


        $picture1 = new Picture(
            $publicFolder . 'image/tests/b1.png',
            'b1.png',
            'picture'
        );
        $picture2 = new Picture(
            $publicFolder . 'image/tests/b2.png',
            'b2.png',
            'picture'
        );
        $picture3 = new Picture(
            $publicFolder . 'image/tests/b3.png',
            'b3.png',
            'picture'
        );
        $picture4 = new Picture(
            $publicFolder . 'image/tests/b4.png',
            'b4.png',
            'picture'
        );


        $newPictureDTO1 = new PictureDTO(
            new UploadedFile(
                $publicFolder . 'image/tests/r1.png',
                'r1.png'
            )
        );
        $newPictureDTO2 = new PictureDTO(
            new UploadedFile(
                $publicFolder . 'image/tests/r2.png',
                'r2.png'
            )
        );
        $newPictureDTO3 = new PictureDTO(
            new UploadedFile(
                $publicFolder . 'image/tests/r3.png',
                'r3.png'
            )
        );


        $oldPictureDTO1 = new PictureDTO(
            new File($publicFolder . 'image/tests/b1.png')
        );
        $oldPictureDTO2 = new PictureDTO(
            new File($publicFolder . 'image/tests/b2.png')
        );



        yield [
            'pictures' => [
                $picture1,
                $picture2
            ],
            'picturesDTO' => [
                $oldPictureDTO1,
                $oldPictureDTO2,
                $newPictureDTO1,
                $newPictureDTO2,
            ],
            'newPictureExpected' => 2,
            'OldPictureExpected' => 0
        ];


        yield [
            'pictures' => [
                $picture1,
                $picture2,
                $picture3,
                $picture4,
            ],
            'picturesDTO' => [
                $newPictureDTO1,
                $oldPictureDTO2,
                $newPictureDTO2,
                $newPictureDTO3,
            ],
            'newPictureExpected' => 3,
            'OldPictureExpected' => 3
        ];


        yield [
            'pictures' => [
                $picture1,
                $picture2,
            ],
            'picturesDTO' => [
                $oldPictureDTO1,
                $newPictureDTO1,
                $newPictureDTO2,
                $newPictureDTO3,
            ],
            'newPictureExpected' => 3,
            'OldPictureExpected' => 1
        ];


        yield [
            'pictures' => [
                0 => $picture1,
                2 => $picture2,
            ],
            'picturesDTO' => [
                0 => $oldPictureDTO1,
                1 => $newPictureDTO1,
                2 => $oldPictureDTO2,
                3 => $newPictureDTO2,
            ],
            'newPictureExpected' => 2,
            'OldPictureExpected' => 0
        ];



        $video1 = new Video('<iframe src="https://www.youtube.com/embed/1"></iframe>');
        $video2 = new Video('<iframe src="https://www.youtube.com/embed/2"></iframe>');
        $video3 = new Video('<iframe src="https://www.youtube.com/embed/3"></iframe>');
        $video4 = new Video('<iframe src="https://www.youtube.com/embed/4"></iframe>');
        $newVideoDTO1 = new VideoDTO('<iframe src="https://www.youtube.com/embed/1new"></iframe>');
        $newVideoDTO2 = new VideoDTO('<iframe src="https://www.youtube.com/embed/2new"></iframe>');
        $newVideoDTO3 = new VideoDTO('<iframe src="https://www.youtube.com/embed/3new"></iframe>');
        $newVideoDTO4 = new VideoDTO('<iframe src="https://www.youtube.com/embed/4new"></iframe>');
        $oldVideoDTO1 = new VideoDTO('<iframe src="https://www.youtube.com/embed/1"></iframe>');
        $oldVideoDTO2 = new VideoDTO('<iframe src="https://www.youtube.com/embed/2"></iframe>');

        yield [
            'videos' => [
                $video1,
                $video2
            ],
            'videosDTO' => [
                $oldVideoDTO1,
                $oldVideoDTO2,
                $newVideoDTO3,
                $newVideoDTO4,
            ],
            'newVideoExpected' => 2,
            'OldVideoExpected' => 0
        ];

        yield [
            'videos' => [
                $video1,
                $video2,
                $video3,
                $video4,
            ],
            'videosDTO' => [
                $newVideoDTO1,
                $oldVideoDTO2,
                $newVideoDTO2,
                $newVideoDTO3,
            ],
            'newVideoExpected' => 3,
            'OldVideoExpected' => 3
        ];

        yield [
            'videos' => [
                $video1,
                $video2,
            ],
            'videosDTO' => [
                $oldVideoDTO1,
                $newVideoDTO1,
                $newVideoDTO2,
                $newVideoDTO2,
            ],
            'newVideoExpected' => 3,
            'OldVideoExpected' => 1
        ];

        yield [
            'videos' => [
                0 => $video1,
                2 => $video2,
            ],
            'videosDTO' => [
                0 => $oldVideoDTO1,
                1 => $newVideoDTO1,
                2 => $oldVideoDTO2,
                3 => $newVideoDTO2,
            ],
            'newVideoExpected' => 2,
            'OldVideoExpected' => 0
        ];
    }
}
