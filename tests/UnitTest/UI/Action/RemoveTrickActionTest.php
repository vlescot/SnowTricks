<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Action;

use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\Service\Image\Interfaces\FolderRemoverInterface;
use App\UI\Action\Interfaces\RemoveTrickActionInterface;
use App\UI\Action\RemoveTrickAction;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RemoveTrickActionTest extends TestCase
{
    /**
     * @var TrickRepositoryInterface
     */
    private $trickRepository;

    /**
     * @var FolderRemoverInterface
     */
    private $folderRemover;

    public function setUp()
    {
        $this->trickRepository = $this->getMockBuilder(TrickRepositoryInterface::class)
            ->setMethods(['__construct', 'findAll', 'save', 'remove', 'findOneBy'])
            ->getMock();
        $this->folderRemover = $this->createMock(FolderRemoverInterface::class);
    }

    private function constructInstance()
    {
        return new RemoveTrickAction(
            $this->trickRepository,
            $this->folderRemover
        );
    }


    public function testConstruct()
    {
        $action = $this->constructInstance();

        static::assertInstanceOf(RemoveTrickActionInterface::class, $action);
    }

    public function testReturnGoodResponse()
    {
        $request = Request::create('/figure/supprimer', 'POST');
        $request->attributes->add(['id' => md5('fake_id')]);

        $trick = $this->createMock(TrickInterface::class);
        $this->trickRepository->method('findOneBy')->willReturn($trick);
        $this->trickRepository->method('remove')->willReturn(null);

        $this->folderRemover->method('removeFolder')->willReturn(true);

        $action = $this->constructInstance();
        $response = $action($request);

        static::assertInstanceOf(Response::class, $response);
        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
    }
}
