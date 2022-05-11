<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('dateOfBirth', DateType::class, [
                "widget" => 'single_text',
                "format" => 'yyyy-MM-dd',
                'data' => new \DateTime(),
            ])
            ->add('imageFile', VichFileType::class, [
                'required' => false,
            ])
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
