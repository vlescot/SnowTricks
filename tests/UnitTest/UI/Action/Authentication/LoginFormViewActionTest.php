<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Action\Authentication;

use App\UI\Action\Authentication\Interfaces\LoginFormViewActionInterface;
use App\UI\Action\Authentication\LoginFormViewAction;
use App\UI\Responder\TwigResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

final class LoginFormViewActionTest extends TestCase
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    public function setUp()
    {
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->authenticationUtils = $this->createMock(AuthenticationUtils::class);
    }

    private function constructInstance()
    {
        return new LoginFormViewAction(
            $this->formFactory,
            $this->urlGenerator,
            $this->authenticationUtils
        );
    }

    public function testConstruct()
    {
        $action = $this->constructInstance();

        static::assertInstanceOf(LoginFormViewActionInterface::class, $action);
    }

    public function testReturnGoodResponse()
    {
        $form = $this->createMock(FormInterface::class);
        $this->formFactory->method('create')->willReturn($form);
        $this->urlGenerator->method('generate')->willReturn('/connexion');
        $this->authenticationUtils->method('getLastUsername')->willReturn('username');
        $twig = $this->createMock(Environment::class);

        $responder = new TwigResponder($twig);

        $action = $this->constructInstance();

        $response = $action($responder);

        static::assertInstanceOf(Response::class, $response);
        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
    }
}
