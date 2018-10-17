<?php
declare(strict_types=1);

namespace App\UI\Action\Authentication;

use App\Domain\Factory\Interfaces\UserDTOFactoryInterface;
use App\UI\Action\Authentication\Interfaces\UpdateUserActionInterface;
use App\UI\Form\Handler\Interfaces\UpdateUserHandlerInterface;
use App\UI\Form\Type\Authentication\UpdateUserType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route(
 *      "/update_user",
 *      name="UpdateUser",
 *      methods={"GET", "POST"}
 * )
 *
 *
 * Class UpdateUser
 * @package App\UI\Action\Authentication
 *
 *
 * This class is used to handle the update user form
 * via modal windows (only one route manage all the modals windows).
 * Then, the forms handling are managed with AuthenticationViewAction
 */
final class UpdateUserAction implements UpdateUserActionInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UpdateUserHandlerInterface
     */
    private $updateUserHandler;

    /**
     * @var UserDTOFactoryInterface
     */
    private $userDTOFactory;

    /**
     * @var UserInterface
     */
    private $user;


    /**
     * UpdateUserAction constructor.
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
    ) {
        $this->formFactory = $formFactory;
        $this->updateUserHandler = $updateUserHandler;
        $this->userDTOFactory = $userDTOFactory;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $updateUserDTO = $this->userDTOFactory->create($this->user);

        $form = $this->formFactory
            ->create(UpdateUserType::class, $updateUserDTO)
            ->handleRequest($request);

        $this->updateUserHandler->handle($form, $this->user);

        return new RedirectResponse($request->headers->get('referer'));
    }
}
