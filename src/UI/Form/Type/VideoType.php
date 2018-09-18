<?php
declare(strict_types = 1);

namespace App\UI\Form\Type;

use App\Domain\DTO\VideoDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('iFrame', TextareaType::class, ['trim' => true]);
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VideoDTO::class,
            'validation' => ['TrickDTO'],
            'error_bubbling' => true,
            'empty_data' => function (FormInterface $form) {
                return new VideoDTO(
                    $form->get('iFrame')->getData()
                );
            }
        ]);
    }
}
