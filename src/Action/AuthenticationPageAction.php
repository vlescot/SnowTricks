<?php

namespace App\Action;

use App\Responder\AuthenticationPageResponder;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/auth/{modal}", name="authentication")
 * @Method({"GET"})
 *
 * Class AuthenticationPageAction
 * @package App\Action
 */
class AuthenticationPageAction
{
    /**
     * @var AuthenticationPageResponder
     */
    private $responder;

    /**
     * AuthenticationPageAction constructor.
     * @param AuthenticationPageResponder $responder
     */
    public function __construct(AuthenticationPageResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * @param $modal
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke($modal)
    {
        if ('connection' === $modal ||
            'registration' === $modal ||
            'reset_password' === $modal ||
            'change_password' === $modal
        ) {
            $responder = $this->responder;
            return $responder($modal);
        } else {
            throw new Exception('404 Error');
        }
    }
}
