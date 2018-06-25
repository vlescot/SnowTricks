<?php

namespace App\Action;

use App\Domain\Repository\TrickRepository;
use App\Form\TrickType;
use App\Responder\AddTrickResponder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/add/trick", name="AddTrick")
 * @Method({"GET", "POST"})
 *
 * Class AddTrickAction
 * @package App\Action
 */
class AddTrickAction extends  Controller
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var AddTrickResponder
     */
    private $responder;

    /**
     * @var TrickRepository
     */
    private $trickRepository;

    /**
     * AddTrickAction constructor.
     * @param FormFactoryInterface $formFactory
     * @param AddTrickResponder $responder
     */
    public function __construct(FormFactoryInterface $formFactory, AddTrickResponder $responder, TrickRepository $trickRepository)
    {
        $this->formFactory = $formFactory;
        $this->responder = $responder;
        $this->trickRepository = $trickRepository;
    }

    /**
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke()
    {
//        $trick = $this->trickRepository->findOneBy(['slug' => 'spins']);

        $form = $this->formFactory->create(TrickType::class);
        $responder = $this->responder;
        return $responder($form);
    }
}
