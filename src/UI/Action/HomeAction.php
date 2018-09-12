<?php
declare(strict_types = 1);

namespace App\UI\Action;

use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\UI\Action\Interfaces\HomeActionInterface;
use App\UI\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/",
 *     name="Home",
 *     methods={"GET"}
 * )
 *
 * Class HomePageAction
 * @package App\Action
 */
final class HomeAction implements HomeActionInterface
{
    /**
     * @var TrickRepositoryInterface
     */
    private $trickRepository;

    /**
     * HomeAction constructor.
     *
     * @inheritdoc
     */
    public function __construct(TrickRepositoryInterface $trickRepository)
    {
        $this->trickRepository = $trickRepository;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(TwigResponderInterface $responder): Response
    {
        $tricks = $this->trickRepository->findAll();

        return $responder(
            'snowtricks/homepage.html.twig', [
                'tricks' => $tricks
            ]
        );
    }
}
