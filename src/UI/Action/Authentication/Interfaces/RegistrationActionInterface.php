<?php
declare(strict_types=1);

namespace App\UI\Action\Authentication\Interfaces;

use App\UI\Form\Handler\Interfaces\RegistrationHandlerInterface;
use App\UI\Responder\Authentication\Interfaces\RegistrationResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RegistrationActionInterface
{
    /**
     * RegistrationActionInterface constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param RegistrationHandlerInterface $registrationHandler
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        RegistrationHandlerInterface $registrationHandler
    );

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response;
}
