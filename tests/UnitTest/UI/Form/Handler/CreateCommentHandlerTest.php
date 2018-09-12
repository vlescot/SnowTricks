<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Form\Handler;

use App\Domain\DTO\CommentDTO;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Repository\Interfaces\CommentRepositoryInterface;
use App\UI\Form\Handler\CreateCommentHandler;
use App\UI\Form\Handler\Interfaces\CreateCommentHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateCommentHandlerTest extends KernelTestCase
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var CommentRepositoryInterface
     */
    private $commentRepository;


    public function setUp()
    {
        static::bootKernel();
        $this->session = $this::$kernel->getContainer()->get('session');

        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->commentRepository = $this->createMock(CommentRepositoryInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $token = $this->createMock(TokenInterface::class);

        $this->validator->method('validate')->willReturn([]);
        $token->method('getUser')->willReturn(
            $this->createMock(UserInterface::class)
        );
        $this->tokenStorage->method('getToken')->willReturn($token);
    }

    private function constructInstance()
    {
        return new CreateCommentHandler(
            $this->validator,
            $this->session,
            $this->tokenStorage,
            $this->commentRepository
        );
    }

    public function testConstruct()
    {
        $createCommentHandler = $this->constructInstance();

        static::assertInstanceOf(CreateCommentHandlerInterface::class, $createCommentHandler);
    }

    public function testHandleReturnTrue()
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn(true);
        $form->method('isValid')->willReturn(true);
        $form->method('getData')->willReturn(new CommentDTO('A Test Comment'));

        $trick = $this->createMock(TrickInterface::class);

        $createCommentHandler = $this->constructInstance();

        static::assertTrue($createCommentHandler->handle($form, $trick));
    }

    public function testHandleReturnFalse()
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn(false);
        $form->method('isValid')->willReturn(false);
        $form->method('getData')->willReturn(new CommentDTO('A Test Comment'));

        $trick = $this->createMock(TrickInterface::class);

        $createCommentHandler = $this->constructInstance();

        static::assertFalse($createCommentHandler->handle($form, $trick));
    }
}
