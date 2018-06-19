<?php

namespace App\Action;

use App\Domain\Entity\Trick;
use App\Responder\HomePageResponder;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppAction
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var HomePageResponder
     */
    private $homePageResponder;

    public function __construct(ManagerRegistry $doctrine, HomePageResponder $homePageResponder)
    {
        $this->doctrine = $doctrine;
        $this->homePageResponder = $homePageResponder;
    }

    /**
     * @Route("/", name="homepage")
     *
     * @return HomePageResponder
     */
    public function index()
    {
        $tricks = $this->doctrine
            ->getRepository(Trick::class)
            ->findAll();

        return new HomePageResponder($tricks);

//        return new Response(
//            $this->render('snowtricks/index.html.twig', [
//                'tricks' => $tricks
//            ])
//        );
    }

    /**
     * @Route("/trick/{slug}", name="trick")
     */
    public function showTrick($slug)
    {
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->findOneBy(['slug' => $slug]);
        //dump($trick);die;
        return new Response(
            $this->render('snowtricks/trick.html.twig', [
                'trick' => $trick,
            ])
        );
    }

    /**
     * @Route("/auth/{modal}", name="authentication")
     */
    public function authentication($modal)
    {
        if ('connection' === $modal || 'registration' === $modal || 'reset_password' === $modal || 'change_password' === $modal) {
            $view = 'auth/'.$modal.'.html.twig';

            return new Response(
                $this->render($view, ['id' => $modal])
            );
        } else {
            return new Response('False');
        }
    }
}
