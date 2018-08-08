<?php
declare(strict_types = 1);

namespace App\UI\Action;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\UI\Form\Type\CreateTrickType;
use App\UI\Form\Handler\CreateTrickHandler;
use App\UI\Responder\CreateTrickResponder;

/**
 * @Route("/snowtrick/creer", name="CreateTrick")
 * @Method({"GET", "POST"})
 *
 * Class AddTrickAction
 * @package App\Action
 */
class CreateTrickAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var CreateTrickHandler
     */
    private $addTrickHandler;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * AddTrickAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param CreateTrickHandler $addTrickHandler
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        CreateTrickHandler $addTrickHandler,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->formFactory = $formFactory;
        $this->addTrickHandler = $addTrickHandler;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Request $request
     * @param SessionInterface $session
     * @param CreateTrickResponder $responder
     *
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        Request $request,
        CreateTrickResponder $responder
    ) :Response {
        $form = $this->formFactory->create(CreateTrickType::class)
                                  ->handleRequest($request);

        if ($this->addTrickHandler->handle($form)) {
            return $responder(true);
        }

        return $responder(false, $form);
    }
}
