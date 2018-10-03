<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Form\Handler;

use App\Domain\Builder\Interfaces\CreateTrickBuilderInterface;
use App\Domain\DTO\GroupDTO;
use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\DTO\PictureDTO;
use App\Domain\DTO\TrickDTO;
use App\Domain\DTO\VideoDTO;
use App\Domain\Entity\Group;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Entity\Picture;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\UI\Form\Handler\CreateTrickHandler;
use App\UI\Form\Handler\Interfaces\CreateTrickHandlerInterface;
use App\App\Image\Interfaces\ImageThumbnailCreatorInterface;
use App\App\Image\Interfaces\ImageUploaderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateTrickHandlerTest extends KernelTestCase
{
    /**
     * @var TrickRepositoryInterface
     */
    private $trickRepository;

    /**
     * @var CreateTrickBuilderInterface
     */
    private $createTrickBuilder;

    /**
     * @var ImageUploaderInterface
     */
    private $imageUploader;

    /**
     * @var ImageThumbnailCreatorInterface
     */
    private $imageThumbnailCreator;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var SessionInterface
     */
    private $session;

    public function setUp()
    {
        $this->trickRepository = $this->createMock(TrickRepositoryInterface::class);
        $this->createTrickBuilder = $this->createMock(CreateTrickBuilderInterface::class);
        $this->imageUploader = $this->createMock(ImageUploaderInterface::class);
        $this->imageThumbnailCreator = $this->createMock(ImageThumbnailCreatorInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        static::bootKernel();
        $this->session = $this::$kernel->getContainer()->get('session');
    }

    private function constructInstance()
    {
        return new CreateTrickHandler(
            $this->trickRepository,
            $this->createTrickBuilder,
            $this->imageUploader,
            $this->imageThumbnailCreator,
            $this->validator,
            $this->session
        );
    }


    public function testConstruct()
    {
        $createTrickHandler = $this->constructInstance();

        static::assertInstanceOf(CreateTrickHandlerInterface::class, $createTrickHandler);
    }


    /**
     * @param TrickInterface $trick
     * @param TrickDTOInterface $trickDTO
     *
     * @dataProvider provideData
     */
    public function testHandleReturnTrue(TrickInterface $trick, TrickDTOInterface $trickDTO)
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn(true);
        $form->method('isValid')->willReturn(true);
        $form->method('getData')->willReturn($trickDTO);

        $this->createTrickBuilder->method('create')->willReturn($trick);
        $this->validator->method('validate')->willReturn([]);

        $createTrickHandler = $this->constructInstance();

        static::assertTrue($createTrickHandler->handle($form));
    }


    /**
     * @param TrickInterface $trick
     * @param TrickDTOInterface $trickDTO
     *
     * @dataProvider provideData
     */
    public function testHandleReturnFalse(TrickInterface $trick, TrickDTOInterface $trickDTO)
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn(false);
        $form->method('isValid')->willReturn(false);

        $createTrickHandler = $this->constructInstance();

        static::assertFalse($createTrickHandler->handle($form));
    }


    /**
     * @return \Generator
     */
    public function provideData()
    {
        static::bootKernel();
        $imagesTestFolder = $this::$kernel->getRootDir() . '/../public/image/tests/';


        $title = 'New Title';
        $description = 'New Description';
        $mainPictureDTO = new PictureDTO(new File($imagesTestFolder . 'r1.png'));

        $picturesDTO = [
            new PictureDTO(new File($imagesTestFolder . 'r1.png')),
            new PictureDTO(new File($imagesTestFolder . 'r2.png')),
            new PictureDTO(new File($imagesTestFolder . 'r3.png'))
        ];

        $videosDTO = [
            new VideoDTO('<iframe src="https://www.youtube.com/embed/1"></iframe>'),
            new VideoDTO('<iframe src="https://www.youtube.com/embed/2"></iframe>'),
            new VideoDTO('<iframe src="https://www.youtube.com/embed/3"></iframe>'),
        ];

        $groups = new ArrayCollection();
        $groups->add(new Group('Jump'));
        $groups->add(new Group('Rotation'));

        $newGroupsDTO = [
            new GroupDTO('New Group'),
            new GroupDTO('Another New Group'),
        ];


        $user = new User();
        $user->registration('NewUser', 'newmail@mail.com', 'azerty00');
        $oldMainPicture = new Picture('image/tests/r1.png', 'r1.png', 'image-b1');


        yield [
            new Trick(
                'Old Title',
                'Old Description',
                $user,
                $oldMainPicture
            ),
            new TrickDTO(
                $title,
                $description,
                $mainPictureDTO,
                $picturesDTO,
                $videosDTO,
                $groups,
                $newGroupsDTO
            )
        ];
    }
}
