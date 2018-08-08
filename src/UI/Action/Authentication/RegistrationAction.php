<?php
declare(strict_types = 1);

namespace App\UI\Action\Authentication;

use App\Domain\Repository\UserRepository;
use App\Service\Mailer;
use App\UI\Form\Handler\RegistrationHandler;
use App\UI\Form\Type\Authentication\RegistrationType;
use App\UI\Responder\Authentication\RegistrationResponder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/registration", name="Registration")
 * @Method({"GET", "POST"})
 *
 * Class RegistrationAction
 * @package App\UI\Action\Auth
 */
class RegistrationAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RegistrationHandler
     */
    private $registrationHandler;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * RegistrationAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param RegistrationHandler $registrationHandler
     * @param Mailer $mailer
     * @param UserRepository $userRepository
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        RegistrationHandler $registrationHandler,
        Mailer $mailer,
        UserRepository $userRepository
    ) {
        $this->formFactory = $formFactory;
        $this->registrationHandler = $registrationHandler;
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }


    /**
     * @param Request $request
     * @param RegistrationResponder $responder
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(Request $request, RegistrationResponder $responder): Response
{
        $form = $this->formFactory->create(RegistrationType::class)
                                  ->handleRequest($request);

        if ($this->registrationHandler->handle($form)) {
            $user = $this->userRepository->loadUserByUsername($form->get('username')->getData());
            $this->mailer->sendMail(
                $user->getEmail(),
                'SnowTricks - Bienvenue sur notre site',
                'email_member_registration_notification.html.twig', [
                    'username' => $user->getUsername(),
                    'token' => $user->getToken()
                ]
            );
        }

        return $responder($request->headers->get('referer'));
    }
}
