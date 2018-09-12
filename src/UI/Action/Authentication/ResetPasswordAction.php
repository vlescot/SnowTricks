<?php
declare(strict_types=1);

namespace App\UI\Action\Authentication;

use App\UI\Action\Authentication\Interfaces\ResetPasswordActionInterface;
use App\UI\Form\Handler\Interfaces\ResetPasswordHandlerInterface;
use App\UI\Form\Type\Authentication\ResetPasswordType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(
 *     "/reset_password",
 *     name="ResetPassword",
 *     methods={"GET", "POST"}
 * )
 *
 * Class ResetPasswordAction
 * @package App\UI\Action\Authentication
 *
 *
 * This class is used to handle the reset password form
 * via modal windows (only one route manage all the modals windows).
 * Then, the forms handling are managed with AuthenticationViewAction
 */
final class ResetPasswordAction implements ResetPasswordActionInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ResetPasswordHandlerInterface
     */
    private $resetPasswordHandler;

    /**
     * ResetPasswordAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param ResetPasswordHandlerInterface $resetPasswordHandler
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        ResetPasswordHandlerInterface $resetPasswordHandler
    ) {
        $this->formFactory = $formFactory;
        $this->resetPasswordHandler = $resetPasswordHandler;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(ResetPasswordType::class)
                                  ->handleRequest($request);

        $this->resetPasswordHandler->handle($form);

        return new RedirectResponse($request->headers->get('referer'));
    }
}
