<?php
declare(strict_types = 1);

namespace App\UI\Action\Authentication;

use App\Domain\DTO\ChangePasswordDTO;
use App\Domain\Repository\UserRepository;
use App\UI\Form\Handler\ChangePasswordHandler;
use App\UI\Form\Type\Authentication\ChangePasswordType;
use App\UI\Responder\Authentication\ChangePasswordResponder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/change-password/{token}", name="ChangePassword")
 * @Method({"GET", "POST"})
 *
 * Class ChangePasswordAction
 * @package App\UI\Action\Authentication
 */
class ChangePasswordAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ChangePasswordHandler
     */
    private $changePasswordHandler;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * ChangePasswordAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param ChangePasswordHandler $changePasswordHandler
     * @param SessionInterface $session
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        ChangePasswordHandler $changePasswordHandler,
        SessionInterface $session
    ) {
        $this->formFactory = $formFactory;
        $this->changePasswordHandler = $changePasswordHandler;
        $this->session = $session;
    }


    /**
     * @param Request $request
     * @param ChangePasswordResponder $responder
     * @return string
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(Request $request, ChangePasswordResponder $responder)
    {
        $token = $request->attributes->get('token');
        $user = $this->userRepository->loadUserByToken($token);

        if (null === $user) {
            $this->session->getBagFlash()->add('danger', 'Nous n\'avons pas pu vous identifier');
            return $responder(true);
        }

        $changePasswordDTO = new ChangePasswordDTO($token);

        $form = $this->formFactory->create(ChangePasswordType::class, $changePasswordDTO)
                                  ->handleRequest($request);

        if ($this->changePasswordHandler->handle($form, $user)){
            return $responder(true);
        }

        return $responder(false, $form);
    }
}
