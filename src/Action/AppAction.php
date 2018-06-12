<?php

namespace App\Action;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppAction extends Controller
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
     * @Route("/create")
     */
    /*    public function create ()
        {
            $trick_name = 'The Ultimate Trick';

            $member = new Member();
            $member->setValidated(0);
            $member->setCateCreate(new \DateTime());
            $member->setEmail('floyd@hotmail.com');
            $member->setUsername('root');
            $member->setPassword('root');


            $trick1 = new Trick();
            $trick1->setName($trick_name);
            $trick1->setContent('Lorem ipsum dolor sit amet.');
            $date = new \DateTime();
            $trick1->setDateCreate($date);
            $trick1->setValidated(0);
            $trick1->setMember($member);

            $path = 'assets/img/$trick_name/';

            $picture1 = new Picture();
            $picture1->setPath($path);
            $picture1->setFile('t1.jpg');
            $picture1->setAlt('t1');
            $picture1->setTrick($trick1);

            $picture2 = new Picture();
            $picture2->setPath($path);
            $picture2->setFile('t2.jpg');
            $picture2->setAlt('t2');
            $picture2->setTrick($trick1);

            $picture2 = new Picture();
            $picture2->setPath($path);
            $picture2->setFile('t2.jpg');
            $picture2->setAlt('t2');
            $picture2->setTrick($trick1);

            $em = $this->getDoctrine()->getManager();
            $em->persist($trick1);
            $em->persist($picture1);
            $em->persist($picture2);
            $em->flush();

            return new Response('Created');
        }*/

    /**
     * @Route("/", name="homepage")
     */
    public function index ()
    {
        return new Response(
            $this->render('snowtrick/index.html.twig', [
                'tricks' => $this->tricks,
            ])
        );
    }

    /**
     * @Route("/trick/{id}", name="trick")
     */
    public function showTrick($id)
    {
        return new Response(
            $this->render('snowtrick/trick.html.twig', [
                'trick' => $this->trick,
                'comments' => $this->comments
            ])
        );
    }

    /**
     * @Route("/auth/{modal}", name="authentication")
     */
    public function authentification ($modal)
    {
        if ($modal === 'connection' || $modal === 'registration' || $modal === 'reset_password' || $modal === 'change_password') {
            $view = 'auth/' . $modal . '.html.twig';
            return new Response(
                $this->render($view, ['id' => $modal])
            );
        }
        else {
            return new Response('False');
        }
    }
}
