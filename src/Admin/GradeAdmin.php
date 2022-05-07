<?php

namespace App\Admin;
use App\Entity\Classe;
use App\Entity\Course;
use App\Entity\Student;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

final class GradeAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('student', EntityType:: class, [
            'class' => Student::class,
            'choice_label' => function($student){
                return $student->getName();
            },
            'choice_value' => function($student){
                if($student != null){
                    return $student->getId();
                }
            }
        ]);
        $form->add('course', ModelType::class, [
            'class' => Course::class,
            'property' => 'name',
        ]);
        $form->add('classe', ModelType::class, [
            'class' => Classe::class,
            'property' => 'name',
        ]);
        $form->add('grade', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('grade');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('student');
        $list->addIdentifier('course');
        $list->addIdentifier('classe');
        $list->addIdentifier('grade');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('student');
        $show->add('course');
        $show->add('classe');
        $show->add('grade');
    }
}