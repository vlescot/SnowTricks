<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\Service\CollectionManager;

use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\Interfaces\VideoBuilderInterface;
use App\Domain\DTO\PictureDTO;
use App\Domain\DTO\VideoDTO;
use App\Domain\Entity\Interfaces\PictureInterface;
use App\Domain\Entity\Interfaces\VideoInterface;
use App\App\Service\CollectionManager\Interfaces\CollectionCheckerInterface;
use App\Domain\CollectionManager\Interfaces\CollectionUpdatePrepareInterface;
use App\Domain\CollectionManager\CollectionUpdateChecker;
use App\Domain\CollectionManager\CollectionUpdatePrepare;
use App\App\Image\Interfaces\ImageRemoverInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class CollectionUpdatePrepareTest extends TestCase
{
    /**
     * @var CollectionCheckerInterface
     */
    private $pictureChecker;

    /**
     * @var ImageRemoverInterface
     */
    private $imageRemover;

    /**
     * @var PictureBuilderInterface
     */
    private $pictureBuilder;

    /**
     * @var VideoBuilderInterface
     */
    private $videoBuilder;

    
    public function setUp()
    {
        $this->pictureChecker = new CollectionUpdateChecker();
        $this->imageRemover = $this->createMock(ImageRemoverInterface::class);

        $picture = $this->createMock(PictureInterface::class);
        $this->pictureBuilder = $this->createMock(PictureBuilderInterface::class);
        $this->pictureBuilder->method('create')->willReturn($picture);

        $video = $this->createMock(VideoInterface::class);
        $this->videoBuilder = $this->createMock(VideoBuilderInterface::class);
        $this->videoBuilder->method('create')->willReturn($video);
    }

    private function constructInstance()
    {
        return new CollectionUpdatePrepare(
            $this->pictureChecker,
            $this->imageRemover,
            $this->pictureBuilder,
            $this->videoBuilder
        );
    }

    public function testConstruct()
    {
        $pictureCollectionPrepare = $this->constructInstance();

        static::assertInstanceOf(CollectionUpdatePrepareInterface::class, $pictureCollectionPrepare);
    }


    /**
     * @param array $collection
     * @param array $collectionDTO
     * @param int $nbExpected
     *
     * @throws \Exception
     *
     * @dataProvider provideData
     */
    public function testPrepareFunctionReturnGoodValues(
        array $collection,
        array $collectionDTO,
        int $nbExpected
    ) {
        $pictureCollectionPrepare = $this->constructInstance();

        $entities = $pictureCollectionPrepare->prepare($collection, $collectionDTO);

        static::assertInternalType('array', $entities);
        static::assertCount($nbExpected, $entities);
    }


    /**
     * @return \Generator
     */
    public function provideData()
    {
        $picture1 = $this->createMock(PictureInterface::class);
        $picture2 = $this->createMock(PictureInterface::class);
        $picture3 = $this->createMock(PictureInterface::class);
        $picture4 = $this->createMock(PictureInterface::class);
        $picture5 = $this->createMock(PictureInterface::class);
        $picture6 = $this->createMock(PictureInterface::class);

        $oldPictureDTO1 = new PictureDTO($this->createMock(\SplFileInfo::class));
        $oldPictureDTO2 = new PictureDTO($this->createMock(\SplFileInfo::class));

        $newPictureDTO1 = new PictureDTO($this->createMock(UploadedFile::class));
        $newPictureDTO2 = new PictureDTO($this->createMock(UploadedFile::class));
        $newPictureDTO3 = new PictureDTO($this->createMock(UploadedFile::class));

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
            'nbExpected' => 4,
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
                $oldPictureDTO1,
                $newPictureDTO2,
                $newPictureDTO3,
            ],
            'nbExpected' => 4,
        ];


        yield [
            'pictures' => [
                0 => $picture1,
                1 => $picture2,
                2 => $picture3,
                3 => $picture4,
                4 => $picture5,
                5 => $picture6,
            ],
            'picturesDTO' => [
                 2 => $oldPictureDTO1,
                 3 => $newPictureDTO1,
                 6 => $newPictureDTO2,
            ],
            'nbExpected' => 3
        ];



        $video1 = $this->createMock(VideoInterface::class);
        $video2 = $this->createMock(VideoInterface::class);
        $video3 = $this->createMock(VideoInterface::class);
        $video4 = $this->createMock(VideoInterface::class);
        $video5 = $this->createMock(VideoInterface::class);
        $video6 = $this->createMock(VideoInterface::class);

        $oldVideoDTO1 = new VideoDTO('<iframe src="https://www.youtube.com/embed/Qyclqo_AV2M"></iframe>');
        $oldVideoDTO2 = new VideoDTO('<iframe src="https://www.youtube.com/embed/Qyclqo_AV2M"></iframe>');

        $newVideoDTO1 = new VideoDTO('<iframe src="https://www.youtube.com/embed/45cYwDMibGo"></iframe>');
        $newVideoDTO2 = new VideoDTO('<iframe src="https://www.youtube.com/embed/45cYwDMibGo"></iframe>');
        $newVideoDTO3 = new VideoDTO('<iframe src="https://www.youtube.com/embed/45cYwDMibGo"></iframe>');

        yield [
            'videos' => [
                $video1,
                $video2
            ],
            'videosDTO' => [
                $oldVideoDTO1,
                $oldVideoDTO2,
                $newVideoDTO1,
                $newVideoDTO2,
            ],
            'nbExpected' => 4,
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
                $oldVideoDTO1,
                $newVideoDTO2,
                $newVideoDTO3,
            ],
            'nbExpected' => 4,
        ];


        yield [
            'videos' => [
                0 => $video1,
                1 => $video2,
                2 => $video3,
                3 => $video4,
                4 => $video5,
                5 => $video6,
            ],
            'videosDTO' => [
                2 => $oldVideoDTO1,
                3 => $newVideoDTO1,
                6 => $newVideoDTO2,
            ],
            'nbExpected' => 3
        ];
    }
}
