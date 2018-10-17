<?php
declare(strict_types = 1);

namespace App\UI\Action;

use App\Domain\Factory\Interfaces\TrickDTOFactoryInterface;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\UI\Action\Interfaces\UpdateTrickActionInterface;
use App\UI\Form\Handler\Interfaces\UpdateTrickHandlerInterface;
use App\UI\Form\Type\UpdateTrickType;
use App\UI\Responder\Interfaces\EditTrickResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/{slug}/modifier",
 *     name="UpdateTrick",
 *     methods={"GET", "POST"}
 * )
 *
 * Class UpdateTrickAction
 * @package App\Action
 */
final class UpdateTrickAction implements UpdateTrickActionInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var TrickRepositoryInterface
     */
    private $trickRepository;

    /**
     * @var UpdateTrickHandlerInterface
     */
    private $updateTrickHandler;

    /**
     * @var TrickDTOFactoryInterface
     */
    private $trickDTOFactory;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @inheritdoc
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        TrickRepositoryInterface $trickRepository,
        UpdateTrickHandlerInterface $updateTrickHandler,
        TrickDTOFactoryInterface $trickDTOFactory,
        SessionInterface $session
    ) {
        $this->formFactory = $formFactory;
        $this->trickRepository = $trickRepository;
        $this->updateTrickHandler = $updateTrickHandler;
        $this->trickDTOFactory = $trickDTOFactory;
        $this->session = $session;
    }


    /**
     * @inheritdoc
     */
    public function __invoke(Request $request, EditTrickResponderInterface $responder): Response
    {
        $trick = $this->trickRepository->findOneBy(['slug' => $request->attributes->get('slug')]);
        $trickDTO = $this->trickDTOFactory->create($trick);

        $form = $this->formFactory->create(UpdateTrickType::class, $trickDTO)
                                  ->handleRequest($request);

        if ($this->updateTrickHandler->handle($form, $trick)) {
            return $responder('update', true);
        }

        return $responder(
            'update',
            false,
            $form,
            $trick->getSlug() ?? $this->session->get('slug')
        );
    }
}
