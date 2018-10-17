<?php
declare(strict_types = 1);

namespace App\UI\Form\Type;

use App\UI\Form\Subscriber\UpdateTrickSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class UpdateTrickType
 * @package App\Form\Type
 */
class UpdateTrickType extends AbstractType
{
    /**
     * @var UpdateTrickSubscriber
     */
    private $updateTrickSubscriber;

    /**
     * UpdateTrickType constructor.
     *
     * @param UpdateTrickSubscriber $updateTrickSubscriber
     */
    public function __construct(UpdateTrickSubscriber $updateTrickSubscriber)
    {
        $this->updateTrickSubscriber = $updateTrickSubscriber;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $option = [])
    {
        $builder
            ->remove('mainPicture')
            ->add('mainPicture', PictureType::class, [
                'required' => false
            ])
            ->addEventSubscriber($this->updateTrickSubscriber)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return CreateTrickType::class;
    }
}
