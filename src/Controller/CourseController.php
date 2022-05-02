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

class CourseController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/courses", name="app_course")
     */
    public function index(): Response
    {
        $courses = $this->getDoctrine()->getRepository(Course::class)->findAll();
        return $this->render('course/index.html.twig', [
            'courses' => $courses
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
