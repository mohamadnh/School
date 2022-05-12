<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Classe;
use App\Form\CourseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CourseController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/courses", name="app_course")
     */
    public function index(Request $request): Response
    {
        $form = $this->createFormBuilder(null)
        ->add('type', ChoiceType::class, [
            'choices' => [
                'name'=>'name',
                'classe'=>'classe'
            ]
        ])
        ->add('query', TextType::class, [
            'required' => false,
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('search', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary'
            ]
        ])->getForm();
    
        $form->handleRequest($request);

        $courses = $this->getDoctrine()->getRepository(Course::class)->findAll();

        if($form->isSubmitted()){

            $data = $form->getData()['query'];
            $type = $form->getData()['type'];

            if($data != null){
                $em = $this->getDoctrine()->getManager();  
                if($type == "classe"){
                    $query = $em->createQuery(
                        'SELECT s
                        FROM App:Course s, App:Classe c
                        WHERE s.id = c.id and c.name = :data'
                    )
                    ->setParameter('data', $data);  
                } else {
                    $query = $em->createQuery(
                        'SELECT s
                        FROM App:Course s
                        WHERE s.'.$type.' = :data'
                    )
                    ->setParameter('data', $data);  
                }
                $courses = $query->getResult();
            }
        }
            
        return $this->render('course/index.html.twig', [
            'courses' => $courses,
            'form' => $form->createView()
        ]);
    }
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/courses/detail/{id}", name="CourseDetail")
     */
    public function detail($id): Response
    {
        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);

        return $this->render('course/detail.html.twig', [
            'course' => $course
        ]);
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/courses/create", name="createCourse")
     */
    public function create(Request $request){

        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();

            $this->addFlash('notice','Submitted Successfully');

            return $this->redirectToRoute('app_course');
        }

        return $this->render('course/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/courses/update/{id}", name="updateCourse")
     */
    public function update(Request $request, $id){

        $course = $this->getDoctrine()->getRepository(Course::class)->find($id);
        $form = $this -> createForm(CourseType::class, $course);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();

            $this->addFlash('notice','Updated Successfully');

            return $this->redirectToRoute('app_course');
        }

        return $this->render('course/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/courses/delete/{id}", name="deleteCourse")
     */
    public function delete($id){
        $data = $this->getDoctrine()->getRepository(Course::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();

        $this->addFlash('notice','Deleted Successfully');

        return $this->redirectToRoute('app_course');
    }
}
