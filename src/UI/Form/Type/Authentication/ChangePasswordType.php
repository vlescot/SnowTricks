<?php
declare(strict_types = 1);

namespace App\UI\Form\Type\Authentication;

use App\Domain\DTO\ChangePasswordDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'required' => true
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangePasswordDTO::class,
            'validation_groups' => ['changePasswordDTO'],
            'empty_data' => function (FormInterface $form) {
                return new ChangePasswordDTO($form->get('password')->getData());
            }
        ]);
    }
}
