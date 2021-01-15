<?php


namespace App\Form;


use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Imię']])
            ->add('lastName', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'Nazwisko']])
            ->add('books', CollectionType::class, ['entry_type' => BookFormType::class,
                'entry_options' => ['attr' => ['class' => ''], 'label' => false], 'by_reference' => false, 'allow_add' => true, 'allow_delete' => true, 'prototype' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Author::class]);
    }
}