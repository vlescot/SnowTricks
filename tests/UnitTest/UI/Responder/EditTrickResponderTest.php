<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Responder;

use App\UI\Responder\Interfaces\EditTrickResponderInterface;
use App\UI\Responder\EditTrickResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final class EditTrickResponderTest extends TestCase
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function setUp()
    {
        $this->twig = $this->createMock(Environment::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->urlGenerator->method('generate')->willReturn('/mute');
    }

    public function testConstruct()
    {
        $updateTrickResponder = new EditTrickResponder(
            $this->twig,
            $this->session,
            $this->urlGenerator
        );

        static::assertInstanceOf(EditTrickResponderInterface::class, $updateTrickResponder);
    }

    /**
     * @param string $view
     *
     * @dataProvider provideData
     */
    public function testReturnWithoutRedirection(string $view)
    {
        $form = $this->createMock(FormInterface::class);

        $updateTrickResponder = new EditTrickResponder(
            $this->twig,
            $this->session,
            $this->urlGenerator
        );

        static::assertInstanceOf(Response::class, $updateTrickResponder($view, false, $form));
    }

    /**
     * @param string $view
     *
     * @dataProvider provideData
     */
    public function testReturnWithRedirection(string $view)
    {
        $form = $this->createMock(FormInterface::class);

        $updateTrickResponder = new EditTrickResponder(
            $this->twig,
            $this->session,
            $this->urlGenerator
        );

        static::assertInstanceOf(RedirectResponse::class, $updateTrickResponder($view, true, $form));
    }


    public function provideData()
    {
        yield ['create'];
        yield ['update'];
    }
}
