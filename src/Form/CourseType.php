<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Classe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('classe', EntityType:: class, [
                'class' => Classe::class,
                'choice_label' => function($classe){
                    return $classe->getName();
                },
                'choice_value' => function($classe){
                    if($classe != null){
                        return $classe->getId();
                    }
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
