<?php

namespace App\Admin;
use App\Entity\Classe;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

final class CourseAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('name', TextType::class);
        $form->add('classe', ModelType::class, [
            'class' => Classe::class,
            'property' => 'name',
        ]);
        $form->add('description', TextareaType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('name');
        $datagrid->add('classe');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('name');
        $list->addIdentifier('classe');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('name');
        $show->add('classe');
        $show->add('description');
    ;
    }
}