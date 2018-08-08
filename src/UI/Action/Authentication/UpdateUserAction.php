<?php
declare(strict_types=1);

namespace App\UI\Action\Authentication;

use App\Domain\Entity\User;
use App\Domain\Factory\UserDTOFactory;
use App\UI\Form\Handler\UpdateUserHandler;
use App\UI\Form\Type\Authentication\UpdateUserType;
use App\UI\Responder\Authentication\ModalResponder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/update_user", name="UpdateUser")
 * @Method({"GET", "POST"})
 *
 * Class UpdateUser
 * @package App\UI\Action\Authentication
 */
class UpdateUserAction extends Controller
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UpdateUserHandler
     */
    private $updateUserHandler;

    /**
     * @var UserDTOFactory
     */
    private $userDTOFactory;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var User
     */
    private $user;


    /**
     * UpdateUserAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param UpdateUserHandler $updateUserHandler
     * @param UserDTOFactory $userDTOFactory
     * @param SessionInterface $session
     * @param UrlGeneratorInterface $urlGenerator
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        UpdateUserHandler $updateUserHandler,
        UserDTOFactory $userDTOFactory,
        SessionInterface $session,
        UrlGeneratorInterface $urlGenerator,
        TokenStorageInterface $tokenStorage
    ) {
        $this->formFactory = $formFactory;
        $this->updateUserHandler = $updateUserHandler;
        $this->userDTOFactory = $userDTOFactory;
        $this->session = $session;
        $this->urlGenerator = $urlGenerator;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    /**
     * @param Request $request
     * @param ModalResponder $responder
     *
     * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function __invoke(Request $request, ModalResponder $responder)
    {
        $updateUserDTO = $this->userDTOFactory->create($this->user);
        $formUrl = $this->urlGenerator->generate('UpdateUser');

        $form = $this->formFactory
            ->create(UpdateUserType::class, $updateUserDTO, ['action' => $formUrl])
            ->handleRequest($request);

        $this->updateUserHandler->handle($form, $this->user);

        // TODO
        return new RedirectResponse($request->headers->get('referer'));
    }
}
