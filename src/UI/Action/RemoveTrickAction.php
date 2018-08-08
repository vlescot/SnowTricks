<?php
declare(strict_types=1);

namespace App\UI\Action;

use App\Domain\Repository\TrickRepository;
use App\UI\Responder\RemoveTrickResponder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{slug}/supprimer", name="RemoveTrick")
 * @Method({"GET", "POST"})
 *
 * Class RemoveTrickAction
 * @package App\UI\Action
 */
class RemoveTrickAction
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * RemoveTrickAction constructor.
     *
     * @param TrickRepository $trickRepository
     * @param SessionInterface $session
     */
    public function __construct(TrickRepository $trickRepository, SessionInterface $session)
    {
        $this->trickRepository = $trickRepository;
        $this->session = $session;
    }

    /**
     * @param Request $request
     * @param RemoveTrickResponder $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, RemoveTrickResponder $responder): Response
    {
        if ($request->isMethod('GET')) {

            $slug = $request->attributes->get('slug');

            $data = [
                'title' => $this->trickRepository->getTitleBySlug($slug),
                'slug' => $request->attributes->get('slug')
            ];

            return $responder(false, $data);

        }
        elseif ($request->isXmlHttpRequest()) {
            if ($this->trickRepository->removeBySlug($request->request->get('slug'))) {
                $data = [ 'content' => '', 'status' => 200 ];
                return $responder(true, $data);
            }
        }

        $this->session->getFlashBag()->add('danger', 'Un probléme est survenu, nous n\'avons pas pu supprimer cette figure');
        $data = ['content' => 'Une erreur est survenue','status' => 500];
        return $responder(true, $data);
    }
}
