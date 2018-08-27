<?php
declare(strict_types = 1);

namespace App\UI\Action;

use App\Domain\Repository\TrickRepository;
use App\Domain\Factory\TrickDTOFactory;
use App\UI\Form\Handler\UpdateTrickHandler;
use App\UI\Form\Type\UpdateTrickType;
use App\UI\Responder\UpdateTrickResponder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/{slug}/modifier", name="UpdateTrick")
 * @Method({"GET" ,"POST"})
 *
 * Class UpdateTrickAction
 * @package App\Action
 */
class UpdateTrickAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var TrickRepository
     */
    private $trickRepository;

    /**
     * @var UpdateTrickHandler
     */
    private $updateTrickHandler;

    /**
     * @var TrickDTOFactory
     */
    private $trickDTOFactory;

    /**
     * UpdateTrickAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param TrickRepository $trickRepository
     * @param UpdateTrickHandler $updateTrickHandler
     * @param TrickDTOFactory $trickDTOFactory
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        TrickRepository $trickRepository,
        UpdateTrickHandler $updateTrickHandler,
        TrickDTOFactory $trickDTOFactory
    ) {
        $this->formFactory = $formFactory;
        $this->trickRepository = $trickRepository;
        $this->updateTrickHandler = $updateTrickHandler;
        $this->trickDTOFactory = $trickDTOFactory;
    }

    /**
     * @param Request $request
     * @param UpdateTrickResponder $responder
     *
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(Request $request, UpdateTrickResponder $responder): Response
    {
        $trick = $this->trickRepository->findOneBy( ['slug' => $request->attributes->get('slug')] );
        $trickDTO = $this->trickDTOFactory->create($trick);

        $form = $this->formFactory->create(UpdateTrickType::class, $trickDTO)
                                  ->handleRequest($request);

        if ($this->updateTrickHandler->handle($form, $trick)) {
            return $responder(true);
        }

        return $responder(false, $form);
    }
}
