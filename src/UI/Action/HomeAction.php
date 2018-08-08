<?php
declare(strict_types = 1);

namespace App\UI\Action;

use App\Domain\Repository\TrickRepository;
use App\UI\Responder\HomeResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/", name="Home")
 * @Method({"GET"})
 *
 * Class HomePageAction
 * @package App\Action
 */
class HomeAction
{
    /**
     * @var TrickRepository
     */
    private $repository;

    /**
     * HomeAction constructor.
     *
     * @param TrickRepository $repository
     */
    public function __construct(TrickRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param HomeResponder $responder
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(HomeResponder $responder): Response
    {
        $tricks = $this->repository->findAll();
        return $responder($tricks);
    }
}
