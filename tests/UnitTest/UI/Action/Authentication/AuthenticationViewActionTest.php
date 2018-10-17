<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Action\Authentication;

use App\UI\Action\Authentication\ModalAuthenticationAction;
use App\UI\Action\Authentication\Interfaces\AuthenticationViewActionInterface;
use App\UI\Responder\TwigResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

final class AuthenticationViewActionTest extends TestCase
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
        $this->formFactory->method('create')->willReturn(
            $this->createMock(FormInterface::class)
        );

        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);

        $this->authenticationUtils = $this->createMock(AuthenticationUtils::class);
        $this->authenticationUtils->method('getLastUsername')->willReturn('username');
    }

    private function constructInstance()
    {
        return new ModalAuthenticationAction(
            $this->formFactory,
            $this->urlGenerator,
            $this->authenticationUtils
        );
    }


    public function testConstruct()
    {
        $action = $this->constructInstance();

        static::assertInstanceOf(AuthenticationViewActionInterface::class, $action);
    }


    /**
     * @param string $modalName
     *
     * @dataProvider provideModalName
     */
    public function testReturnResponse(string $modalName)
    {
        $request = Request::create('/authentication/update_user', 'GET');
        $request->attributes->add(['modal' => $modalName]);

        $action = $this->constructInstance();

        $twig = $this->createMock(Environment::class);
        $responder = new TwigResponder($twig);

        $response = $action($request, $responder);

        static::assertInstanceOf(Response::class, $response);
        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
    }


    public function provideModalName()
    {
        return [
            ['login'],
            ['registration'],
            ['change_password'],
            ['reset_password'],
            ['update_user']
        ];
    }
}
