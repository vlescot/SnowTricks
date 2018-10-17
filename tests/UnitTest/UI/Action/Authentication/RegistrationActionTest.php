<?php
declare(strict_types=1);

namespace Tests\UnitTest\UI\Action\Authentication;

use App\UI\Action\Authentication\Interfaces\RegistrationActionInterface;
use App\UI\Action\Authentication\RegistrationAction;
use App\UI\Form\Handler\Interfaces\RegistrationHandlerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RegistrationActionTest extends TestCase
{
    /**
     * @var FormFactoryInterface|null
     */
    private $formFactoryMock = null;

    /**
     * @var RegistrationHandlerInterface|null
     */
    private $registrationHandlerMock = null;


    public function setUp()
    {
        $this->formFactoryMock = $this->createMock(FormFactoryInterface::class);
        $this->registrationHandlerMock = $this->createMock(RegistrationHandlerInterface::class);
    }

    public function testImplements()
    {
        $action = new RegistrationAction($this->formFactoryMock, $this->registrationHandlerMock);

        static::assertInstanceOf(RegistrationActionInterface::class, $action);
    }

    public function testReturnsRedirectResponse()
    {
        $request = Request::create('/registration', 'POST');
        $request->headers->add(['referer' => '/']);

        $registrationHandlerMock = $this->createMock(RegistrationHandlerInterface::class);
        $formInterfaceMock = $this->createMock(FormInterface::class);

        $this->formFactoryMock->method('create')->willReturn($formInterfaceMock);
        $formInterfaceMock->method('handleRequest')->willReturn($formInterfaceMock);
        $registrationHandlerMock->method('handle')->willReturn(true);


        $registrationAction = new RegistrationAction($this->formFactoryMock, $registrationHandlerMock);
        $response = $registrationAction($request);

        static::assertInstanceOf(RedirectResponse::class, $registrationAction($request));
        static::assertSame(Response::HTTP_FOUND, $response->getStatusCode());
    }
}
