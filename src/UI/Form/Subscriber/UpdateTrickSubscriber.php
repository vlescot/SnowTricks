<?php
declare(strict_types = 1);

namespace App\UI\Form\Subscriber;

use App\Service\CollectionManager\Interfaces\CollectionCheckerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class UpdateTrickSubscriber
 * @package App\UI\Form\Subscriber
 */
class UpdateTrickSubscriber implements EventSubscriberInterface
{
    private $collectionChecker;

    public function __construct(CollectionCheckerInterface $collectionChecker)
    {
        $this->collectionChecker = $collectionChecker;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $picturesDTO = $event->getForm()->get('pictures')->getData();

        foreach ($picturesDTO as $key => $pictureDTO) {
            if (array_key_exists($key, $data['pictures']) && $data['pictures'][$key]['file'] === null) {
                $data['pictures'][$key]['file'] = $picturesDTO[$key]->file;
            }
        }
        foreach ($data['pictures'] as $key => $dataPicture) {
            if ($dataPicture['file'] === null) {
                unset($data['pictures'][$key]);
            }
        }

        $event->setData($data);
    }
}
