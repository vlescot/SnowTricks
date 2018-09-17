<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Action;

use App\UI\Action\CreateTrickAction;
use App\UI\Action\Interfaces\CreateTrickActionInterface;
use App\UI\Form\Handler\Interfaces\CreateTrickHandlerInterface;
use App\UI\Responder\TwigOrRedirectionResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final class CreateTrickActionTest extends KernelTestCase
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var CreateTrickHandlerInterface
     */
    private $createTrickHandler;


    public function setUp()
    {
        static::bootKernel();

        $this->formFactory = $this::$kernel->getContainer()->get('form.factory');
        $this->createTrickHandler = $this->createMock(CreateTrickHandlerInterface::class);
    }

    public function testConstruct()
    {
        $createTrickAction = new CreateTrickAction(
            $this->formFactory,
            $this->createTrickHandler
        );

        static::assertInstanceOf(CreateTrickActionInterface::class, $createTrickAction);
    }

    public function testFormGoodHandling()
    {
        $twig = $this->createMock(Environment::class);
        $session = $this->createMock(SessionInterface::class);
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/spins');


        $request = Request::create('/figure/creer', 'GET');

        $responder  = new TwigOrRedirectionResponder(
            $twig,
            $session,
            $urlGenerator
        );

        $createTrickAction = new CreateTrickAction(
            $this->formFactory,
            $this->createTrickHandler
        );

        $this->createTrickHandler->method('handle')->willReturn(true);
        $response = $createTrickAction($request, $responder);

        static::assertInstanceOf(RedirectResponse::class, $response);
        static::assertSame(Response::HTTP_FOUND, $response->getStatusCode());
    }

    public function testFormWrongHandling()
    {
        $twig = $this->createMock(Environment::class);
        $session = $this->createMock(SessionInterface::class);
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);

        $request = Request::create('/figure/creer', 'GET');

        $responder  = new TwigOrRedirectionResponder(
            $twig,
            $session,
            $urlGenerator
        );

        $createTrickAction = new CreateTrickAction(
            $this->formFactory,
            $this->createTrickHandler
        );

        $this->createTrickHandler->method('handle')->willReturn(false);
        $response = $createTrickAction($request, $responder);

        static::assertInstanceOf(Response::class, $response);
        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
    }
}
