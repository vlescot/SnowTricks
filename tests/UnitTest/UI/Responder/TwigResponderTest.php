<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Responder;

use App\UI\Responder\Interfaces\TwigResponderInterface;
use App\UI\Responder\TwigResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class TwigResponderTest extends TestCase
{
    /**
     * @var Environment
     */
    private $twig;

    public function setUp()
    {
        $this->twig = $this->createMock(Environment::class);
    }

    private function constructInstance()
    {
        return new TwigResponder($this->twig);
    }


    public function testConstruct()
    {
        $responder = $this->constructInstance();

        static::assertInstanceOf(TwigResponderInterface::class, $responder);
    }

    public function testReturnGoodValue()
    {
        $formView = $this->createMock(FormInterface::class);

        $responder = $this->constructInstance();

        $response = $responder('view.html.twig', [
            'form' => $formView
        ]);

        static::assertInstanceOf(Response::class, $response);
    }
}
