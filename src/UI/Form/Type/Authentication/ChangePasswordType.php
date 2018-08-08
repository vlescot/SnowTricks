<?php
declare(strict_types = 1);

namespace App\UI\Form\Type\Authentication;

use App\Domain\DTO\ChangePasswordDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, ['required' => true]);
        $builder->add('token', HiddenType::class, ['required' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangePasswordDTO::class,
            'empty_data' => function (FormInterface $form, ChangePasswordDTO $passwordDTO) {
                $passwordDTO->token = $form->get('token')->getData();
                $passwordDTO->password = $form->get('password')->getData();
            }
        ]);
    }
}
