<?php
declare(strict_types = 1);

namespace App\UI\Form\Type;

use App\Domain\DTO\PictureDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreatePictureType
 * @package App\UI\Form\Type
 */
class PictureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('file', FileType::class, [
            'attr' => [
                'accept' => '.jpg, .jpeg, .png'
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PictureDTO::class,
            'validation_groups' => ['trickDTO'],
            'error_bubbling' => true,
            'empty_data' => function (FormInterface $form) {
                return new PictureDTO(
                    $form->get('file')->getData()
                );
            }
        ]);
    }
}
