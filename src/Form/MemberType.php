<?php

namespace App\Form;

use App\Entity\Member;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;



class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('job', TextType::class, [
                'label' => 'Job'
            ])
            ->add('birthDate',DateType::Class, array(
                'widget' => 'choice',
                'label' => 'Date de naissance',
                'format' =>'dd/MM/yyyy',
                'years' => range(date('Y')-70, date('Y')+2),
            ))
            ->add('picture', TextType::class, [
                'label' => 'Nom de la photo'
            ])
            ->add('band')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}