<?php
declare(strict_types=1);

namespace App\UI\Action\Authentication\Interfaces;

use App\Domain\Factory\Interfaces\UserDTOFactoryInterface;
use App\UI\Form\Handler\Interfaces\UpdateUserHandlerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

interface UpdateUserActionInterface
{
    /**
     * UpdateUserActionInterface constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param UpdateUserHandlerInterface $updateUserHandler
     * @param UserDTOFactoryInterface $userDTOFactory
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        UpdateUserHandlerInterface $updateUserHandler,
        UserDTOFactoryInterface $userDTOFactory,
        TokenStorageInterface $tokenStorage
    );

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response;
}
