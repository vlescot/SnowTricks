<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Form\Handler;

use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\Interfaces\UpdateTrickBuilderInterface;
use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;
use App\UI\Form\Handler\Interfaces\UpdateTrickHandlerInterface;
use App\UI\Form\Handler\UpdateTrickHandler;
use App\Service\Image\Interfaces\FolderChangerInterface;
use App\Service\Image\Interfaces\ImageRemoverInterface;
use App\Service\Image\Interfaces\ImageThumbnailCreatorInterface;
use App\Service\Image\Interfaces\ImageUploaderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdateTrickHandlerTest extends TestCase
{
    /**
     * @var TrickRepositoryInterface
     */
    private $trickRepository;

    /**
     * @var ImageRemoverInterface
     */
    private $imageRemover;

    /**
     * @var ImageUploaderInterface
     */
    private $imageUploader;

    /**
     * @var ImageThumbnailCreatorInterface
     */
    private $thumbnailCreator;

    /**
     * @var FolderChangerInterface
     */
    private $folderChanger;

    /**
     * @var UpdateTrickBuilderInterface
     */
    private $updateTrickBuilder;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ImageUploadWarmerInterface
     */
    private $imageUploadWarmer;

    /**
     * @var PictureBuilderInterface
     */
    private $pictureBuilder;



    public function setUp()
    {
        $this->trickRepository = $this->createMock(TrickRepositoryInterface::class);
        $this->imageRemover = $this->session = $this->createMock(ImageRemoverInterface::class);
        $this->imageUploader = $this->createMock(ImageUploaderInterface::class);
        ;
        $this->thumbnailCreator = $this->createMock(ImageThumbnailCreatorInterface::class);
        $this->folderChanger = $this->session = $this->createMock(FolderChangerInterface::class);
        $this->updateTrickBuilder = $this->createMock(UpdateTrickBuilderInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->imageUploadWarmer = $this->createMock(ImageUploadWarmerInterface::class);
        $this->pictureBuilder = $this->createMock(PictureBuilderInterface::class);
    }

    private function constructInstance()
    {
        return new UpdateTrickHandler(
            $this->trickRepository,
            $this->imageRemover,
            $this->imageUploader,
            $this->thumbnailCreator,
            $this->folderChanger,
            $this->updateTrickBuilder,
            $this->validator,
            $this->session,
            $this->imageUploadWarmer,
            $this->pictureBuilder
        );
    }


    public function testConstruct()
    {
        $createTrickHandler = $this->constructInstance();

        static::assertInstanceOf(UpdateTrickHandlerInterface::class, $createTrickHandler);
    }


    public function TestHandleReturnTrue()
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn(true);
        $form->method('isValid')->willReturn(true);

        $trick = $this->createMock(TrickInterface::class);
        $trickDTO = $this->createMock(TrickDTOInterface::class);

        $this->updateTrickBuilder->method('update')->willReturn($trickDTO);
        $this->validator->method('validate')->willReturn([]);


        $createTrickHandler = $this->constructInstance();

        static::assertTrue($createTrickHandler->handle($form, $trick));
    }


    public function testHandleReturnFalse()
    {
        $trick = $this->createMock(TrickInterface::class);
        $trickDTO = $this->createMock(TrickDTOInterface::class);
        $form = $this->createMock(FormInterface::class);

        $this->updateTrickBuilder->method('update')->willReturn($trickDTO);

        $createTrickHandler = $this->constructInstance();

        static::assertFalse($createTrickHandler->handle($form, $trick));
    }
}
