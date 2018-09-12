<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Action;

use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\UI\Action\HomeAction;
use App\UI\Action\Interfaces\HomeActionInterface;
use App\UI\Responder\TwigResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class HomeActionTest extends TestCase
{
    /**
     * @var TrickRepositoryInterface
     */
    private $trickRepositoryMock;

    public function setUp()
    {
        $this->trickRepositoryMock = $this->createMock(TrickRepositoryInterface::class);

        $this->trickRepositoryMock->method('findAll')->willReturn([
            $this->createMock(TrickInterface::class),
            $this->createMock(TrickInterface::class),
            $this->createMock(TrickInterface::class),
        ]);
    }

    public function testConstruct()
    {
        $homeAction = new HomeAction($this->trickRepositoryMock);

        static::assertInstanceOf(HomeActionInterface::class, $homeAction);
    }

    public function testInvokeFunction()
    {
        $responder = new TwigResponder(
            $this->createMock(Environment::class)
        );

        $homeAction = new HomeAction($this->trickRepositoryMock);
        $response = $homeAction($responder);

        static::assertInstanceOf(Response::class, $response);
        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
    }
}
