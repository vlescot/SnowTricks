<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Form\Handler;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Form\Handler\ChangePasswordHandler;
use App\UI\Form\Handler\Interfaces\ChangePasswordHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ChangePasswordHandlerTest extends KernelTestCase
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

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
        static::bootKernel();

        $this->passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->session = $this::$kernel->getContainer()->get('session');
    }

    private function constructInstance()
    {
        return new ChangePasswordHandler(
            $this->passwordEncoder,
            $this->userRepository,
            $this->validator,
            $this->session
        );
    }

    public function testConstruct()
    {
        $changePasswordHandler = $this->constructInstance();

        static::assertInstanceOf(ChangePasswordHandlerInterface::class, $changePasswordHandler);
    }


    public function testHandleReturnTrue()
    {
        $user = $this->getMockBuilder(UserInterface::class)
            ->setMethods(['changePassword', 'getRoles', 'getPassword', 'getSalt', 'getUsername', 'eraseCredentials'])
            ->getMock();
        $user->method('changePassword')->willReturn(true);

        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn(true);
        $form->method('isValid')->willReturn(true);

        $form->method('getData')->willReturn('azerty00');
        $form->method('get')->willReturn($form);

        $this->passwordEncoder->method('encodePassword')->willReturn(md5('azerty00'));
        $this->validator->method('validate')->willReturn([]);


        $changePasswordHandler = $this->constructInstance();

        static::assertTrue($changePasswordHandler->handle($form, $user));
    }


    public function testHandleReturnFalse()
    {
        $user = $this->createMock(UserInterface::class);

        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn(false);
        $form->method('isValid')->willReturn(false);


        $changePasswordHandler = $this->constructInstance();

        static::assertFalse($changePasswordHandler->handle($form, $user));
    }
}
