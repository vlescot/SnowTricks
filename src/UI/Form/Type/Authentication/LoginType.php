<?php
declare(strict_types = 1);

namespace App\UI\Form\Type\Authentication;

use App\Domain\DTO\UserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_login', TextType::class, [
                'required' => true,
                'trim' => true
            ])
            ->add('_password', PasswordType::class, [
                'required' => true,
                'trim' => true
            ])
        ;
    }
}
