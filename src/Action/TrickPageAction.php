<?php

namespace App\Action;

use App\Domain\Entity\Trick;
use App\Domain\Repository\TrickRepository;
use App\Responder\TrickPageResponder;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/trick/{slug}", name="Trick")
 * @Method({"GET"})
 *
 * Class TrickPageAction
 * @package App\Action
 */
class TrickPageAction
{
    /**
     * @var TrickRepository
     */
    private $repository;

    /**
     * @var TrickPageResponder
     */
    private $responder;

    public function __construct(TrickRepository $repository, TrickPageResponder $responder)
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
    public function __invoke($slug)
    {
        $responder = $this->responder;
        $trick = $this->repository->findOneBy(['slug' => $slug]);
        return $responder($trick);
    }
}
