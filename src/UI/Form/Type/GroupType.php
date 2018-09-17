<?php
declare(strict_types = 1);

namespace App\UI\Form\Type;

use App\Domain\DTO\GroupDTO;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder
            ->add('name', TextType::class, [
                'trim' => true,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GroupDTO::class,
            'validation_groups' => ['TrickDTO'],
            'error_bubbling' => true,
            'empty_data' => function (FormInterface $form) {
                return new GroupDTO(
                    $form->get('name')->getData()
                );
            },
        ]);
    }
}
