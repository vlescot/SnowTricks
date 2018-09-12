<?php
declare(strict_types = 1);

namespace App\UI\Form\Type\Authentication;

use App\Domain\DTO\UserDTO;
use App\UI\Form\Type\PictureType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RegistrationType
 * @package App\UI\Form\Type\Auth
 */
class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['required' => true])
            ->add('email', EmailType::class, ['required' => true])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
            ])
            ->add('picture', PictureType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserDTO::class,
            'validation_groups' => ['userDTO'],
            'empty_data' => function (FormInterface $form) {
                return new UserDTO(
                    $form->get('username')->getData(),
                    $form->get('email')->getData(),
                    $form->get('password')->getData(),
                    $form->get('picture')->getData()
                );
            },
        ]);
    }
}
