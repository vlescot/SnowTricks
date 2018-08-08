<?php
declare(strict_types=1);

namespace App\Tests\UI\Handler;

use App\Domain\Builder\GroupBuilder;
use App\Domain\Builder\PictureBuilder;
use App\Domain\Builder\CreateTrickBuilder;
use App\Domain\Builder\VideoBuilder;
use App\Domain\DTO\PictureDTO;
use App\Domain\DTO\TrickDTO;
use App\Domain\Entity\Picture;
use App\Domain\Entity\User;
use App\Domain\Repository\TrickRepository;
use App\Domain\Repository\UserRepository;
use App\Service\Image\ImageUploadWarmer;
use App\UI\Form\Handler\CreateTrickHandler;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateTrickHandlerTest extends TestCase
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;
    /**
     * @var ImageHandler
     */
    private $imageHandler;
    /**
     * @var ImageUploadWarmer
     */
    private $imageHelper;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var PictureBuilder
     */
    private $pictureBuilder;
    /**
     * @var VideoBuilder
     */
    private $videoBuilder;
    /**
     * @var GroupBuilder
     */
    private $groupBuilder;
    /**
     * @var CreateTrickBuilder
     */
    private $trickBuilder;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var FormFactoryInterface
     */
    private $formInterface;
    /**
     * @var CreateTrickHandler
     */
    private $createTrickHandler;

    public function setUp()
    {
        $this->trickRepository = $this->createMock(TrickRepository::class);
        $this->imageHandler = $this->createMock(ImageHandler::class);
        $this->imageHelper = $this->createMock(ImageUploadWarmer::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->pictureBuilder = $this->createMock(PictureBuilder::class);
        $this->videoBuilder = $this->createMock(VideoBuilder::class);
        $this->groupBuilder = $this->createMock(GroupBuilder::class);
        $this->trickBuilder = $this->createMock(CreateTrickBuilder::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->session = $this->createMock(SessionInterface::class);

        $this->formInterface = $this->createMock(FormInterface::class);
        $this->createTrickHandler = new CreateTrickHandler(
            $this->trickRepository,
            $this->imageHandler,
            $this->imageHelper,
            $this->userRepository,
            $this->pictureBuilder,
            $this->videoBuilder,
            $this->groupBuilder,
            $this->trickBuilder,
            $this->validator,
            $this->session
        );
    }

    public function testConstruct()
    {
        static::assertInstanceOf(CreateTrickHandler::class, $this->createTrickHandler);
    }


    /**
     * @param TrickDTO $trickDTO
     * @throws \Exception
     */
    public function testHandleReturnTrue(TrickDTO $trickDTO)
    {

        $userMock = $this->createMock(User::class);
        $uploadedFileMock = $this->createMock(UploadedFile::class);

        $this->formInterface->method('isValid')->willReturn(true);
        $this->formInterface->method('isSubmitted')->willReturn(true);
        $this->formInterface->method('getData')->willReturn($trickDTO);

        $this->imageHelper->method('generateImageInfo')->willReturn([
            'path' => '/image/trick/' . strtolower(str_replace(' ', '_', $trickDTO->title)),
            'filename' => md5(uniqid('', true)) .'.jpg',
            'alt' => 'image--' . strtolower(str_replace(' ', '-', $trickDTO->title))
        ]);

//        $this->pictureBuilder->method('create')->willReturn($this->createMock(Picture::class));

        $pictureInfo = $this->imageHelper->generateImageInfo($uploadedFileMock);

        static::assertInternalType('array', $pictureInfo);

        $picture = $this->pictureBuilder->create(
            $pictureInfo,
            false
        );
        static::assertInstanceOf(Picture::class, $picture);

        $mainPictureInfo = $this->imageHelper
            ->initialize($trickDTO->title, $this->imageHelper::TRICK_FOLDER)
            ->generateImageInfo($uploadedFileMock);

        $picturesInfo = [];
        foreach ($trickDTO->pictures as $pictureDTO) {
            if ($pictureDTO->file !== null) {
                $pictureInfo = $this->imageHelper->generateImageInfo($uploadedFileMock);
                $picturesInfo[] = $pictureInfo;
            }
        }

        $this->trickRepository->save($this->trickBuilder->create(
            $trickDTO->title,
            $trickDTO->description,
            $userMock,
            $this->pictureBuilder->create($mainPictureInfo, false),
            $this->pictureBuilder->create($picturesInfo, true),
            $this->videoBuilder->create($trickDTO->videos, true),
            $this->groupBuilder->create($trickDTO->groups, $trickDTO->newGroups)
        ));

        static::assertInstanceOf(
            CreateTrickHandler::class,
            $this->createTrickHandler
        );

        static ::assertTrue(
            $this->createTrickHandler->handle($this->formInterface)
        );
    }

    public function goodTricksProvider()
    {
        $mainPictureDTOMock = $this->createMock(PictureDTO::class);
        $arrayCollection = $this->createMock(ArrayCollection::class);

        return [
            [
                new TrickDTO(
                    'New Title',
                    'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                    $mainPictureDTOMock,
                    [],
                    [],
                    $arrayCollection,
                    []
                )
            ],
        ];
    }
}
