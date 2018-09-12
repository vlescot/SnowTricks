<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Action\Authentication;

use App\UI\Action\Authentication\Interfaces\ResetPasswordActionInterface;
use App\UI\Action\Authentication\ResetPasswordAction;
use App\UI\Form\Handler\Interfaces\ResetPasswordHandlerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ResetPasswordActionTest extends TestCase
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ResetPasswordHandlerInterface
     */
    private $resetPasswordHandler;


    public function setUp()
    {
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->resetPasswordHandler = $this->createMock(ResetPasswordHandlerInterface::class);
    }

    private function constructInstance()
    {
        return new ResetPasswordAction(
            $this->formFactory,
            $this->resetPasswordHandler
        );
    }


    public function testConstruct()
    {
        $action = $this->constructInstance();

        static::assertInstanceOf(ResetPasswordActionInterface::class, $action);
    }


    public function testReturnGoodResponse()
    {
        $request = Request::create('/reset_password', 'POST', [], [], [], ['HTTP_REFERER' => '/']);

        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturn($form);
        $this->formFactory->method('create')->willReturn($form);

        $this->resetPasswordHandler->method('handle')->willReturn(true);

        $action = $this->constructInstance();

        $response = $action($request);

        static::assertInstanceOf(RedirectResponse::class, $response);
        static::assertSame(Response::HTTP_FOUND, $response->getStatusCode());
    }
}
