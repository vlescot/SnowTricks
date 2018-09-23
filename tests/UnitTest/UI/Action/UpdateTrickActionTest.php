<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Action;

use App\Domain\DTO\PictureDTO;
use App\Domain\DTO\TrickDTO;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Factory\Interfaces\TrickDTOFactoryInterface;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\UI\Action\Interfaces\UpdateTrickActionInterface;
use App\UI\Action\UpdateTrickAction;
use App\UI\Form\Handler\Interfaces\UpdateTrickHandlerInterface;
use App\UI\Responder\TwigOrRedirectionResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final class UpdateTrickActionTest extends KernelTestCase
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var TrickRepositoryInterface
     */
    private $trickRepository;

    /**
     * @var UpdateTrickHandlerInterface
     */
    private $updateTrickHandler;

    /**
     * @var TrickDTOFactoryInterface
     */
    private $trickDTOFactory;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var string
     */
    private $imageFolder;


    public function setUp()
    {
        static::bootKernel();

        $this->imageFolder = $this::$kernel->getRootDir() . '/../public/image/';

        $this->formFactory = $this::$kernel->getContainer()->get('form.factory');
        $this->updateTrickHandler = $this->createMock(UpdateTrickHandlerInterface::class);
        $this->trickRepository = $this->getMockBuilder(TrickRepositoryInterface::class)
            ->setMethods(['__construct', 'findAll', 'save', 'remove', 'findOneBy'])
            ->getMock();
        $this->trickDTOFactory = $this->getMockBuilder(TrickDTOFactoryInterface::class)
            ->setMethods(['__construct', 'create'])
            ->getMock();
        $this->session = $this->createMock(SessionInterface::class);

        $this->trickRepository->method('findOneBy')->willReturn(
            $this->createMock(TrickInterface::class)
        );
    }

    public function testConstruct()
    {
        $updateTrickAction = new UpdateTrickAction(
            $this->formFactory,
            $this->trickRepository,
            $this->updateTrickHandler,
            $this->trickDTOFactory,
            $this->session
        );

        static::assertInstanceOf(UpdateTrickActionInterface::class, $updateTrickAction);
    }


    public function testFormGoodHandling()
    {
        $mainPictureDTO = new PictureDTO(
            new File($this->imageFolder . 'tests/b1.png')
        );

        $trickDTO =   new TrickDTO(
            'Old Title',
            'Old Description',
            $mainPictureDTO
        );


        $twig = $this->createMock(Environment::class);
        $session = $this->createMock(SessionInterface::class);
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/mute');

        $this->trickDTOFactory->method('create')->willReturn($trickDTO);

        $request = Request::create('/mute/modifier', 'GET');

        $responder  = new TwigOrRedirectionResponder(
            $twig,
            $session,
            $urlGenerator
        );

        $updateTrickAction = new UpdateTrickAction(
            $this->formFactory,
            $this->trickRepository,
            $this->updateTrickHandler,
            $this->trickDTOFactory,
            $this->session
        );

        $this->updateTrickHandler->method('handle')->willReturn(true);

        static::assertInstanceOf(RedirectResponse::class, $updateTrickAction($request, $responder));
    }


    public function testFormWrongHandling()
    {
        $mainPictureDTO = new PictureDTO(
            new File($this->imageFolder . 'tests/b1.png')
        );

        $trickDTO =   new TrickDTO(
            'Old Title',
            'Old Description',
            $mainPictureDTO
        );


        $twig = $this->createMock(Environment::class);
        $session = $this->createMock(SessionInterface::class);
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/mute');

        $this->trickDTOFactory->method('create')->willReturn($trickDTO);

        $request = Request::create('/mute/modifier', 'GET');

        $responder  = new TwigOrRedirectionResponder(
            $twig,
            $session,
            $urlGenerator
        );

        $updateTrickAction = new UpdateTrickAction(
            $this->formFactory,
            $this->trickRepository,
            $this->updateTrickHandler,
            $this->trickDTOFactory,
            $this->session
        );

        $this->updateTrickHandler->method('handle')->willReturn(false);

        $response = $updateTrickAction($request, $responder);

        static::assertInstanceOf(Response::class, $response);
        static::assertSame(Response::HTTP_OK, $response->getStatusCode());
    }
}
