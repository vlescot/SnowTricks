<?php
declare(strict_types=1);

namespace App\UI\Form\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UpdateUserSubscriber implements EventSubscriberInterface
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
        $updateUserDTO = $event->getForm()->getData();
        $data = $event->getData();

        if ($updateUserDTO->picture->file instanceof \SplFileInfo && $data['picture']['file'] === null) {
            $data['picture']['file'] = $updateUserDTO->picture->file;
        }

        $event->setData($data);
    }
}
