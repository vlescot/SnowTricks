<?php
declare(strict_types=1);

namespace App\Tests\Domain\CollectionManager\CollectionChecker;

use App\Domain\DTO\PictureDTO;
use App\Domain\Entity\Picture;
use PHPUnit\Framework\TestCase;
use App\Domain\CollectionManager\CollectionChecker\PictureCollectionChecker;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureCollectionCheckerTest extends TestCase
{
    /**
     * @var PictureCollectionChecker
     */
    private $pictureCollectionChercker;

    /**
     * @var Picture
     */
    private $firstEntity;

    /**
     * @var Picture
     */
    private $secondEntity;

    /**
     * @var Picture
     */
    private $thirdEntity;

    /**
     * @var PictureDTO
     */
    private $firstOldDTO;

    /**
     * @var PictureDTO
     */
    private $secondOldDTO;

    /**
     * @var PictureDTO
     */
    private $firstNewDTO;

    /**
     * @var PictureDTO
     */
    private $secondNewDTO;


    public function setUp()
    {
        $this->pictureCollectionChercker = $this->createMock(PictureCollectionChecker::class);

        $this->firstEntity = $this->createMock(Picture::class);
        $this->secondEntity = $this->createMock(Picture::class);
        $this->thirdEntity = $this->createMock(Picture::class);


        $this->firstOldDTO = $this->createMock(PictureDTO::class);
        $this->firstOldDTO->file = ($this->createMock(File::class));
        $this->secondOldDTO = $this->createMock(PictureDTO::class);
        $this->secondOldDTO->file = ($this->createMock(File::class));
        $this->firstNewDTO = $this->createMock(PictureDTO::class);
        $this->firstNewDTO->file = ($this->createMock(UploadedFile::class));
        $this->secondNewDTO = $this->createMock(PictureDTO::class);
        $this->secondNewDTO->file = ($this->createMock(UploadedFile::class));

    }

    /**
     * @dataProvider compareProvider
     *
     * @param array $pictures
     * @param array $picturesDTO
     */
    public function testPictureCollectionCheckerContainsGoodValues(array $pictures, array $picturesDTO)
    {
        $this->pictureCollectionChercker->compare($pictures, $picturesDTO);
        $newObjectArray = $this->pictureCollectionChercker->getNewObject();
        $dirtyObjectArray = $this->pictureCollectionChercker->getDirtyObject();
        $deletedObjectArray = $this->pictureCollectionChercker->getDeletedObject();

        static::assertArrayHasKey(1, $deletedObjectArray);
        static::assertArrayHasKey(2, $dirtyObjectArray);
        static::assertCount(1, count($newObjectArray));
    }

    public function compareProvider()
    {
        return [
            [
                [
                    0 => $this->firstEntity,
                    1 => $this->secondEntity,
                    2 => $this->thirdEntity
                ],
                [
                    0 => $this->firstOldDTO,        // Good entity nothing to return
                                                    // Removing deleted object with key 1
                    2 => $this->firstNewDTO,        // Dirty entity with key 2
                    3 => $this->secondOldDTO,       // Good entity nothing to return
                    4 => $this->secondNewDTO        // New Entity, key doesn't matter
                ]
            ],
        ];
    }
}
