<?php
declare(strict_types = 1);

namespace App\UI\Form\Type;

use App\Domain\DTO\CommentDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentDTO::class,
            'validation_groups' => ['commentDTO'],
            'empty_data' => function (FormInterface $form) {
                return new CommentDTO(
                    $form->get('content')->getData()
                );
            }
        ]);
    }
}
