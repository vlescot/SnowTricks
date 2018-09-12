<?php
declare(strict_types = 1);

namespace App\UI\Action;

use App\UI\Action\Interfaces\CreateTrickActionInterface;
use App\UI\Form\Handler\Interfaces\CreateTrickHandlerInterface;
use App\UI\Responder\Interfaces\CrUpTrickResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\UI\Form\Type\CreateTrickType;

/**
 * @Route(
 *     "/figure/creer",
 *     name="CreateTrick",
 *     methods={"GET", "POST"}
 * )
 *
 * Class AddTrickAction
 * @package App\Action
 */
final class CreateTrickAction implements CreateTrickActionInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var CreateTrickHandlerInterface
     */
    private $addTrickHandler;

    /**
     * CreateTrickAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param CreateTrickHandlerInterface $addTrickHandler
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        CreateTrickHandlerInterface $addTrickHandler
    ) {
        $this->formFactory = $formFactory;
        $this->addTrickHandler = $addTrickHandler;
    }

    /**
     * @param Request $request
     * @param CrUpTrickResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, CrUpTrickResponderInterface $responder) :Response
    {
        $form = $this->formFactory->create(CreateTrickType::class)
                                  ->handleRequest($request);

        if ($this->addTrickHandler->handle($form)) {
            return $responder('create', true);
        }

        return $responder('create', false, $form);
    }
}
