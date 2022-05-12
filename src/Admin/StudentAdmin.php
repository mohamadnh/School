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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Form\Type\VichFileType;

final class StudentAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
        ->with('Content', ['class' => 'col-md-9'])
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('classe', ModelType::class, [
                'class' => Classe::class,
                'property' => 'name',
            ])
            ->add('dateOfBirth', DateTimeType::class, [
            "widget" => 'single_text',
            "format" => 'yyyy-MM-dd',
            'data' => new \DateTime(),
            ])
        ->end();
        $form
        ->with('Meta data', ['class' => 'col-md-3'])
            ->add('imageFile', VichFileType::class, [
                'required' => false,
            ])
        ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('firstName');
        $datagrid->add('lastName');
        $datagrid->add('dateOfBirth');
        $datagrid->add('classe');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('image', NULL , array('template' => 'image_list.html.twig'));
        $list->addIdentifier('firstName');
        $list->addIdentifier('lastName');
        $list->addIdentifier('dateOfBirth');
        $list->addIdentifier('classe');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('image', NULL , array('template' => 'image.html.twig'));
        $show->add('firstName');
        $show->add('lastName');
        $show->add('dateOfBirth');
        $show->add('classe');
    }
}