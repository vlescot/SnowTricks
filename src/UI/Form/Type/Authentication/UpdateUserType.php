<?php
declare(strict_types=1);

namespace App\UI\Form\Type\Authentication;

use App\Domain\DTO\UpdateUserDTO;
use App\UI\Form\Subscriber\UpdateUserSubscriber;
use App\UI\Form\Type\PictureType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateUserType extends AbstractType
{
    /**
     * @var UpdateUserSubscriber
     */
    private $subscriber;

    /**
     * UpdateUserType constructor.
     *
     * @param UpdateUserSubscriber $subscriber
     */
    public function __construct(UpdateUserSubscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => false
            ])
            ->add('password', RepeatedType::class, [
                'required' => false,
                'type' => PasswordType::class
            ])
            ->add('picture', PictureType::class, [
                'required' => false
            ])
            ->addEventSubscriber($this->subscriber)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UpdateUserDTO::class,
            'validation_groups' => ['updateUserDTO'],
            'empty_data' => function (FormInterface $form) {
                $userDTO =  new UpdateUserDTO(
                    $form->get('email')->getData(),
                    $form->get('password')->getData(),
                    $form->get('picture')->getData()
                );
                return $userDTO;
            },
        ]);
    }
}
