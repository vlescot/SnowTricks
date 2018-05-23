<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ViewsController extends Controller
{
    private $tricks;
    private $trick;
    private $comments;

    public function __construct ()
    {
        /*
         *  PAGE D'ACCUEIL
         */
        $tricks = [
            [
                'id' => 0,
                'title' => 'The Great Trick',
                'content' => 'Lorem ipsum dolor sit amet.',
                'img' => '1.jpg'
            ],
            [
                'id' => 1,
                'title' => 'The Ultimate Trick',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium enim, illo architecto ipsum.'   ,
                'img' => '2.jpg'             
            ],
            [
                'id' => 2,
                'title' => 'The Most Trick',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium enim.',
                'img' => '3.jpg'
            ]
        ];

        $this->tricks = $tricks;
        $n = 0;
        while ($n <= 10) {
            $n++;
            foreach ($tricks as $trick) {
                array_push($this->tricks, $trick);
            }
        }

        /*
         *      PAGE TRICK
         */
        $this->trick = [
            'title' => 'Mute',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Et quisquam repellat commodi voluptates! Nostrum, dignissimos id cumque! Molestias, dolorem ut asperiores numquam eius dolore consequatur ex, atque impedit tempora quibusdam. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident dignissimos exercitationem placeat nemo ad perferen dis harum pariatur similique iusto sapiente ea repudiandae iste non dicta, inventore earum quos sequi soluta?',
            'img' => [
                [ 'path' => 't3.jpg', 'alt' => 'trdzaick' ],
                [ 'path' => 't1.jpg', 'alt' => 'trickzad' ],
                [ 'path' => 't2.jpg', 'alt' => 'trdzaick' ],
                [ 'path' => 't3.jpg', 'alt' => 'trdzaick' ],
                [ 'path' => 't4.jpg', 'alt' => 'trfgick' ],
                [ 'path' => 't6.jpg', 'alt' => 'trgeick' ],
                [ 'path' => 't1.jpg', 'alt' => 'trickzad' ],
                [ 'path' => 't5.jpg', 'alt' => 'trigezck' ],
                [ 'path' => 't6.jpg', 'alt' => 'trgeick' ],


            ],
            'video' => [
                'https://www.youtube.com/embed/6Q7kljjRE6U',
                'https://www.youtube.com/embed/cVKamPWu_Sc',
                'https://www.youtube.com/embed/nMAvJtpNvJI',
            ],
            'date_create' => date("d M Y", mt_rand(1, time())),
            'date_update' => date("d M Y", mt_rand(1, time())),
            'author'      => 'SnowMan',
            'group' => [ 'grab', 'spin', 'slide' ]
        ];

        $this->comments = [
            'comments'  => [
                'author' => 'Igloo',
                'date' =>   date("d M Y", mt_rand(1, time())),
                'avatar' => 'a1.jpg',
                'alt' => 'a1.jpg',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.'
            ],
            [
                'author' => 'Grizzly',
                'date' =>   date("d M Y", mt_rand(1, time())),
                'avatar' => 'a2.jpg',
                'alt' => 'a2.jpg',
                'content' => 'Provident dignissimos exercitationem placeat nemo ad perferendis harum pariatur similique iusto sapiente ea repudiandae iste non dicta'
            ],
            [
                'author' => 'InBoard',
                'date' =>   date("d M Y", mt_rand(1, time())),
                'avatar' => 'a3.jpg',
                'alt' => 'a3.jpg',
                'content' => 'Inventore earum quos sequi soluta?'
            ]
        ];
    }

    /**
     * @Route("/", name="welcome")
     */
    public function index (Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $nb = $request->query->get('nb');
            $tricks = array_slice($this->tricks, 15, $nb);

            return $this->render('snowtrick/trick_thumbnail.html.twig', [
                'tricks' => $tricks,
            ]);
            // return new JsonResponse(['tricks' => json_encode($tricks)]);
        }

        // Recupére les 15 premieres valeurs du tableau
        $tricks = array_slice($this->tricks, 0, 15);
        return $this->render('snowtrick/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    /**
     * @Route("/trick/{id}", name="trick")
     */
    public function showTrick($id)
    {
    	return $this->render('snowtrick/trick.html.twig', [
            'trick' => $this->trick,
            'comments' => $this->comments
        ]);
    }

    /**
     * @Route("/connexion", name="connexion")
     */
    public function connexion()
    {
        return $this->render('auth/connection.html.twig');
    }

    /**
     * @Route("/registration", name="registration")
     */
    public function registration()
    {
        return $this->render('auth/registration.html.twig');
    }

    /**
     * @Route("/reset_password", name="reset_password")
     */
    public function resetPassword()
    {
        return $this->render('auth/reset_password.html.twig');
    }
}
