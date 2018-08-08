<?php
declare(strict_types = 1);

namespace App\UI\Form\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class UpdateTrickSubscriber
 * @package App\UI\Form\Subscriber
 */
class UpdateTrickSubscriber implements EventSubscriberInterface
{
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
        $trickDTO = $event->getForm()->getData();
        $data = $event->getData();

        if ($trickDTO->mainPicture->file instanceof \SplFileInfo && $data['mainPicture']['file'] === null) {
            $data['mainPicture']['file'] = $trickDTO->mainPicture->file;
        }

        foreach ($data['pictures'] as $key => $dataPicture) {
            if (isset($trickDTO->pictures[$key])
                && $trickDTO->pictures[$key]->file instanceof \SplFileInfo
                && $dataPicture['file'] === null
            ) {
                $data['pictures'][$key]['file'] = $trickDTO->pictures[$key]->file;
            }
        }

        $event->setData($data);
    }
}
