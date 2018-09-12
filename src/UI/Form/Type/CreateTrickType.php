<?php
declare(strict_types = 1);

namespace App\UI\Form\Type;

use App\Domain\DTO\TrickDTO;
use App\Domain\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddTrickType
 * @package App\Form\Type
 */
class CreateTrickType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $option
     */
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add('title', TextType::class, ['trim' => true])
            ->add('description', TextareaType::class)
            ->add('mainPicture', PictureType::class, [
                'required' => true
            ])
            ->add('groups', EntityType::class, [
                'class' => Group::class,
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('newGroups', CollectionType::class, [
                'entry_type' => GroupType::class,
                'allow_add' => true,
                'prototype' => true
            ])
            ->add('pictures', CollectionType::class, [
                'entry_type' => PictureType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'entry_options' => [
                    'required' => false
                ]
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'prototype' => true,
                'by_reference' => false,
                'entry_options' => [
                    'required' => false
                ]
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickDTO::class,
            'validation_groups' => ['trickDTO'],
            'empty_data' => function (FormInterface $form) {
                return new TrickDTO(
                    $form->get('title')->getData(),
                    $form->get('description')->getData(),
                    $form->get('mainPicture')->getData(),
                    $form->get('pictures')->getData(),
                    $form->get('videos')->getData(),
                    $form->get('groups')->getData(),
                    $form->get('newGroups')->getData()
                );
            },
        ]);
    }
}
