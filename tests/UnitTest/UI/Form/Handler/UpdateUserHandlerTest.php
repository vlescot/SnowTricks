<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Form\Handler;

use App\Domain\Builder\Interfaces\UpdateUserBuilderInterface;
use App\Domain\DTO\Interfaces\UpdateUserDTOInterface;
use App\Domain\Entity\Interfaces\PictureInterface;
use App\Domain\Repository\Interfaces\PictureRepositoryInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Service\Image\Interfaces\ImageRemoverInterface;
use App\Service\Image\Interfaces\ImageThumbnailCreatorInterface;
use App\Service\Image\Interfaces\ImageUploaderInterface;
use App\UI\Form\Handler\Interfaces\UpdateUserHandlerInterface;
use App\UI\Form\Handler\UpdateUserHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdateUserHandlerTest extends KernelTestCase
{
    /**
     * @var UpdateUserBuilderInterface
     */
    private $updateUserBuilder;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var PictureRepositoryInterface
     */
    private $pictureRepository;

    /**
     * @var ImageUploaderInterface
     */
    private $imageUploader;

    /**
     * @var ImageThumbnailCreatorInterface
     */
    private $thumbnailCreator;

    /**
     * @var ImageRemoverInterface
     */
    private $imageRemover;


    public function setUp()
    {
        static::bootKernel();

        $this->updateUserBuilder = $this->createMock(UpdateUserBuilderInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->session = $this::$kernel->getContainer()->get('session');
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->pictureRepository = $this->createMock(PictureRepositoryInterface::class);
        $this->imageUploader = $this->createMock(ImageUploaderInterface::class);
        $this->thumbnailCreator = $this->createMock(ImageThumbnailCreatorInterface::class);
        $this->imageRemover = $this->createMock(ImageRemoverInterface::class);
    }

    private function constructInstance()
    {
        return new UpdateUserHandler(
            $this->updateUserBuilder,
            $this->validator,
            $this->session,
            $this->userRepository,
            $this->pictureRepository,
            $this->imageUploader,
            $this->thumbnailCreator,
            $this->imageRemover
        );
    }


    public function testConstruct()
    {
        $updateUserHandler = $this->constructInstance();

        static::assertInstanceOf(UpdateUserHandlerInterface::class, $updateUserHandler);
    }

    public function testHandleReturnTrue()
    {
        $oldUser = $this->getMockBuilder(UserInterface::class)
            ->setMethods(['getPicture', 'getRoles', 'getPassword', 'getSalt', 'getUsername', 'eraseCredentials'])
            ->getMock();
        $oldUser->method('getPicture')->willReturn(
            $this->createMock(PictureInterface::class)
        );

        $user = $this->getMockBuilder(UserInterface::class)
            ->setMethods(['getPicture', 'getRoles', 'getPassword', 'getSalt', 'getUsername', 'eraseCredentials'])
            ->getMock();
        $user->method('getPicture')->willReturn(
            $this->createMock(PictureInterface::class)
        );

        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn(true);
        $form->method('isValid')->willReturn(true);
        $form->method('getData')->willReturn(
            $this->createMock(UpdateUserDTOInterface::class)
        );

        $this->updateUserBuilder->method('create')->willReturn($user);

        $this->validator->method('validate')->willReturn([]);


        $updateUserHandler = $this->constructInstance();

        static::assertTrue($updateUserHandler->handle($form, $oldUser));
    }


    public function testHandleReturnFalse()
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn(false);
        $form->method('isValid')->willReturn(false);

        $oldUser = $this->createMock(UserInterface::class);


        $updateUserHandler = $this->constructInstance();

        static::assertFalse($updateUserHandler->handle($form, $oldUser));
    }
}
