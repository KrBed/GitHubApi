<?php


namespace App\Form;


use App\Entity\Book;
use App\Entity\Repository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepositoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, ['required'=>true,'label' => false, 'attr' => ['placeholder' => 'Nazwa repozytorium']])
        ->add('description',TextareaType::class, ['label' => false, 'attr' => ['placeholder'=>'Opis','row' => '20', 'style' => 'min-height:150px'],'required'=>false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Repository::class,
        ]);
    }
}