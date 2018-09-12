<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Action\Authentication;

use App\Domain\DTO\Interfaces\UpdateUserDTOInterface;
use App\Domain\Factory\Interfaces\UserDTOFactoryInterface;
use App\UI\Action\Authentication\Interfaces\UpdateUserActionInterface;
use App\UI\Action\Authentication\UpdateUserAction;
use App\UI\Form\Handler\Interfaces\UpdateUserHandlerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UpdateUserActionTest extends TestCase
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UpdateUserHandlerInterface
     */
    private $updateUserHandler;

    /**
     * @var UserDTOFactoryInterface
     */
    private $userDTOFactory;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;


    public function setUp()
    {
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->updateUserHandler = $this->createMock(UpdateUserHandlerInterface::class);
        $this->userDTOFactory = $this->createMock(UserDTOFactoryInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->user = $this->createMock(UserInterface::class);

        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn($this->user);
        $this->tokenStorage->method('getToken')->willReturn($token);
    }

    private function constructInstance()
    {
        return new UpdateUserAction(
            $this->formFactory,
            $this->updateUserHandler,
            $this->userDTOFactory,
            $this->tokenStorage
        );
    }


    public function testConstruct()
    {
        $action = $this->constructInstance();

        static::assertInstanceOf(UpdateUserActionInterface::class, $action);
    }


    public function testReturnGoodResponse()
    {
        $request = Request::create('/update_user', 'GET', [], [], [], ['HTTP_REFERER' => '/']);

        $userDto = $this->createMock(UpdateUserDTOInterface::class);

        $this->userDTOFactory->method('create')->willReturn($userDto);

        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturn($form);
        $this->formFactory->method('create')->willReturn($form);

        $this->updateUserHandler->method('handle')->willReturn(true);


        $action = $this->constructInstance();

        $response = $action($request);

        static::assertInstanceOf(RedirectResponse::class, $response);
        static::assertSame(Response::HTTP_FOUND, $response->getStatusCode());
    }
}
