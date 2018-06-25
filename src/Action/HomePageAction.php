<?php

namespace App\Action;

use App\Domain\Repository\TrickRepository;
use App\Responder\HomePageResponder;
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
class HomePageAction
{
    /**
     * @var TrickRepository
     */
    private $repository;

    /**
     * @var HomePageResponder
     */
    private $responder;

    public function __construct(TrickRepository $repository, HomePageResponder $responder)
    {
        $this->repository = $repository;
        $this->responder = $responder;
    }

    /**
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke()
    {
        $responder = $this->responder;
        $tricks = $this->repository->findAll();
        return $responder($tricks);
    }
}
