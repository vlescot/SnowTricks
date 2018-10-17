<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Action;

use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\UI\Action\Interfaces\TrickPageActionInterface;
use App\UI\Action\TrickPageAction;
use App\UI\Form\Handler\Interfaces\CreateCommentHandlerInterface;
use App\UI\Responder\TwigResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;

final class TrickPageActionTest extends KernelTestCase
{
    /**
     * @var TrickRepositoryInterface
     */
    private $trickRepository;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var CreateCommentHandlerInterface
     */
    private $commentHandler;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function setUp()
    {
        $this->trickRepository = $this->getMockBuilder(TrickRepositoryInterface::class)
            ->setMethods(['__construct', 'findOneBy', 'findAll', 'remove', 'save'])
            ->getMock();
        $this->commentHandler = $this->createMock(CreateCommentHandlerInterface::class);
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);

        static::bootKernel();

        $this->formFactory = $this::$kernel->getContainer()->get('form.factory');

        $this->trickRepository->method('findOneBy')->willReturn(
            $this->createMock(TrickInterface::class)
        );
    }

    private function constructInstance()
    {
        return new TrickPageAction(
            $this->trickRepository,
            $this->formFactory,
            $this->commentHandler,
            $this->authorizationChecker
        );
    }


    public function testConstruct()
    {
        $trickPageAction = new TrickPageAction(
            $this->trickRepository,
            $this->formFactory,
            $this->commentHandler,
            $this->authorizationChecker
        );

        static::assertInstanceOf(TrickPageActionInterface::class, $trickPageAction);
    }


    public function testInvokeFunctionWithGetANDAuthorizationFalse()
    {
        $request = Request::create('/trickName', 'GET');

        $this->authorizationChecker->method('isGranted')->willReturn(false);

        $responder = new TwigResponder($this->createMock(Environment::class));

        $trickPageAction = $this->constructInstance();

        $response = $trickPageAction($request, $responder);

        static::assertInstanceOf(Response::class, $response);
        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
    }


    public function testInvokeFunctionWithAuthorizationTrue()
    {
        $request = Request::create('/trickName', 'GET');

        $this->authorizationChecker->method('isGranted')->willReturn(true);

        $responder = new TwigResponder($this->createMock(Environment::class));

        $trickPageAction = $this->constructInstance();

        $response = $trickPageAction($request, $responder);

        static::assertInstanceOf(Response::class, $response);
        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
    }


    public function testInvokeFunctionWithAuthorizationTrueANDHandlingFormTrue()
    {
        $request = Request::create('/trickName', 'POST');

        $this->authorizationChecker->method('isGranted')->willReturn(true);
        $this->commentHandler->method('handle')->willReturn(true);

        $responder = new TwigResponder($this->createMock(Environment::class));

        $trickPageAction = $this->constructInstance();

        $response = $trickPageAction($request, $responder);

        static::assertInstanceOf(Response::class, $response);
        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
    }


    public function testInvokeFunctionWithAuthorizationTrueANDHandlingFormFalse()
    {
        $request = Request::create('/trickName', 'POST');

        $this->authorizationChecker->method('isGranted')->willReturn(true);
        $this->commentHandler->method('handle')->willReturn(false);

        $responder = new TwigResponder($this->createMock(Environment::class));

        $trickPageAction = $this->constructInstance();

        $response = $trickPageAction($request, $responder);

        static::assertInstanceOf(Response::class, $trickPageAction($request, $responder));
        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
    }
}
