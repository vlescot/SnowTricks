<?php
declare(strict_types = 1);

namespace App\UI\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var string
     */
    private $targetUrl;

    /**
     * LoginFormAuthenticator constructor.
     *
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param UrlGeneratorInterface $urlGenerator
     * @param SessionInterface $session
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        CsrfTokenManagerInterface $csrfTokenManager,
        UrlGeneratorInterface $urlGenerator,
        SessionInterface $session,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->urlGenerator = $urlGenerator;
        $this->session = $session;
        $this->passwordEncoder = $passwordEncoder;
    }


    /**
     * @inheritdoc
     */
    public function supports(Request $request)
    {
        $this->targetUrl = $request->headers->get('referer');

        if ($request->attributes->get('_route') === 'Login' && $request->isMethod('POST')) {
            if (preg_match('/\/connexion/i', $this->targetUrl) > 0) {
                $this->targetUrl = $this->urlGenerator->generate('Home');
            }
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getCredentials(Request $request)
    {
        $loginFormResult = $request->request->get('login');

        $login = $loginFormResult['_login'];
        $password = $loginFormResult['_password'];
        $csrfToken = $loginFormResult['_token'];

        if (false === $this->csrfTokenManager->isTokenValid(new CsrfToken('login', $csrfToken))) {
            throw new InvalidCsrfTokenException('Invalid CSRF token.');
        }

        $request->getSession()->set(Security::LAST_USERNAME, $login);

        return [
            'login' => $login,
            'password' => $password
        ];
    }

    /**
     * @inheritdoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $login = $credentials['login'];

        try {
            $user =  $userProvider->loadUserByUsername($login);
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add('danger', 'Nous n\'avons pas trouvé de membre avec l\'identifiant ' . $login);
            throw new UsernameNotFoundException();
        }

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        if ($password = $this->passwordEncoder->isPasswordValid($user, $credentials['password'])) {
            return true;
        }

        $this->session->getFlashBag()->add('danger', 'Votre mot de passe est invalide');
        throw new BadCredentialsException();
    }

    /**
     * @inheritdoc
     */
    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('LoginForm');
    }

    /**
     * @inheritdoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $this->session->getFlashBag()->add('success', 'Bonjour ' . $token->getUser()->getUsername());
        return new RedirectResponse($this->targetUrl ?? $this->urlGenerator->generate('Home'));
    }

    /**
     * @inheritdoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        return new RedirectResponse($this->urlGenerator->generate('LoginForm'));
    }
}
