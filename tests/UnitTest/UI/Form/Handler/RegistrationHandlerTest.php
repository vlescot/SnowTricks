<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Form\Handler;

use App\Domain\Builder\Interfaces\UserBuilderInterface;
use App\Domain\DTO\Interfaces\UserDTOInterface;
use App\Domain\DTO\UserDTO;
use App\Domain\Entity\User;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\App\Image\Interfaces\ImageThumbnailCreatorInterface;
use App\App\Image\Interfaces\ImageUploaderInterface;
use App\App\Interfaces\MailerInterface;
use App\UI\Form\Handler\Interfaces\RegistrationHandlerInterface;
use App\UI\Form\Handler\RegistrationHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RegistrationHandlerTest extends KernelTestCase
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var UserBuilderInterface
     */
    private $userBuilder;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ImageUploaderInterface
     */
    private $imageUploader;

    /**
     * @var ImageThumbnailCreatorInterface
     */
    private $thumbnailCreator;

    /**
     * @var MailerInterface
     */
    private $mailer;


    public function setUp()
    {
        static::bootKernel();

        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->userBuilder = $this->createMock(UserBuilderInterface::class);
        $this->session = $this::$kernel->getContainer()->get('session');
        $this->imageUploader = $this->createMock(ImageUploaderInterface::class);
        $this->thumbnailCreator = $this->createMock(ImageThumbnailCreatorInterface::class);
        $this->mailer = $this->createMock(MailerInterface::class);
    }


    private function constructInstance()
    {
        return new RegistrationHandler(
            $this->validator,
            $this->userRepository,
            $this->userBuilder,
            $this->session,
            $this->imageUploader,
            $this->thumbnailCreator,
            $this->mailer
        );
    }


    public function testConstruct()
    {
        $registrationHandler = $this->constructInstance();

        static::assertInstanceOf(RegistrationHandlerInterface::class, $registrationHandler);
    }


    /**
     * @param UserInterface $user
     * @param UserDTOInterface $userDTO
     *
     * @dataProvider provideData
     */
    public function testHandleReturnTrue(UserInterface $user, UserDTOInterface $userDTO)
    {
        $usernameForm = $this->createMock(FormInterface::class);
        $usernameForm->method('getData')->willReturn('username');

        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn(true);
        $form->method('isValid')->willReturn(true);
        $form->method('getData')->willReturn($userDTO);
        $form->method('get')->willReturn($usernameForm);

        $this->userBuilder->method('create')->willReturn($user);
        $this->validator->method('validate')->willReturn([]);
        $this->mailer->method('sendMail')->willReturn(true);


        $registrationHandler = $this->constructInstance();

        static::assertTrue($registrationHandler->handle($form));
    }


    public function testHandleReturnFalse()
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn(false);
        $form->method('isValid')->willReturn(true);

        $registrationHandler = $this->constructInstance();

        static::assertFalse($registrationHandler->handle($form));
    }


    public function provideData()
    {
        $user = new User();
        $user->registration(
            'username',
            'username@mail.com',
            'azerty00'
        );

        $userDTO = new UserDTO(
            'username',
            'username@mail.com',
            'azerty00'
        );

        yield [$user, $userDTO];
    }
}
