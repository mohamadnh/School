<?php

namespace App\Form;

use App\Entity\Student;
use App\Entity\Course;

use App\Entity\StudentsGrades;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class StudentsGradesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('grade')
            ->add('student', EntityType:: class, [
                'class' => Student::class,
                'choice_label' => function($student){
                    return $student->getName();
                },
                'choice_value' => function($student){
                    if($student != null){
                        return $student->getId();
                    }
                }
            ])
            ->add('course', EntityType:: class, [
                'class' => Course::class,
                'choice_label' => function($course){
                    return $course->getName();
                },
                'choice_value' => function($course){
                    if($course != null){
                        return $course->getId();
                    }
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StudentsGrades::class,
        ]);
    }
}
