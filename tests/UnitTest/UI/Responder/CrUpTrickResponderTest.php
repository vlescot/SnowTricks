<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Responder;

use App\UI\Responder\Interfaces\CrUpTrickResponderInterface;
use App\UI\Responder\CrUpTrickResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final class CrUpTrickResponderTest extends TestCase
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
        $this->urlGenerator->method('generate')->willReturn('/spins');
    }

    public function testConstruct()
    {
        $updateTrickResponder = new CrUpTrickResponder(
            $this->twig,
            $this->session,
            $this->urlGenerator
        );

        static::assertInstanceOf(CrUpTrickResponderInterface::class, $updateTrickResponder);
    }

    /**
     * @param string $view
     *
     * @dataProvider provideData
     */
    public function testReturnWithoutRedirection(string $view)
    {
        $form = $this->createMock(FormInterface::class);

        $updateTrickResponder = new CrUpTrickResponder(
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

        $updateTrickResponder = new CrUpTrickResponder(
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
