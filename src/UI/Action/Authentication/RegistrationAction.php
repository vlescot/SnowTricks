<?php
declare(strict_types = 1);

namespace App\UI\Action\Authentication;

use App\UI\Action\Authentication\Interfaces\RegistrationActionInterface;
use App\UI\Form\Handler\Interfaces\RegistrationHandlerInterface;
use App\UI\Form\Type\Authentication\RegistrationType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/registration",
 *     name="Registration",
 *     methods={"POST"}
 * )
 *
 * Class RegistrationAction
 * @package App\UI\Action\Auth
 *
 *
 * This class is used to handle the registration form
 * via modal windows (only one route manage all the modals windows).
 * Then, the forms handling are managed with AuthenticationViewAction
 */
final class RegistrationAction implements RegistrationActionInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RegistrationHandlerInterface
     */
    private $registrationHandler;


    /**
     * RegistrationAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param RegistrationHandlerInterface $registrationHandler
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        RegistrationHandlerInterface $registrationHandler
    ) {
        $this->formFactory = $formFactory;
        $this->registrationHandler = $registrationHandler;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(RegistrationType::class)
                                  ->handleRequest($request);

        $this->registrationHandler->handle($form);

        return new RedirectResponse($request->headers->get('referer'));
    }
}
