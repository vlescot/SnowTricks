<?php
declare(strict_types=1);

namespace App\UI\Action\Authentication\Interfaces;

use App\UI\Form\Handler\Interfaces\ResetPasswordHandlerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ResetPasswordActionInterface
{
    /**
     * ResetPasswordActionInterface constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param ResetPasswordHandlerInterface $resetPasswordHandler
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        ResetPasswordHandlerInterface $resetPasswordHandler
    );

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response;
}
