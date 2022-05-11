<?php

namespace App\Controller;
use App\Entity\Student;
use App\Form\StudentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class StudentsController extends AbstractController
{
    
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/students", name="app_students")
     */
    public function index(): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_USER');
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        return $this->render('students/index.html.twig', [
            'students' => $students
        ]);
    }
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/students/detail/{id}", name="studentDetail")
     */
    public function detail($id): Response
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);

        return $this->render('students/detail.html.twig', [
            'student' => $student
        ]);
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/students/create", name="createStudent")
     */
    public function create(Request $request){

        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();

            $this->addFlash('notice','Submitted Successfully');

            return $this->redirectToRoute('app_students');
        }

        return $this->render('students/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/students/update/{id}", name="updateStudent")
     */
    public function update(Request $request, $id){

        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        $form = $this -> createForm(StudentType::class, $student);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();

            $this->addFlash('notice','Updated Successfully');

            return $this->redirectToRoute('app_students');
        }

        return $this->render('students/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/students/delete/{id}", name="deleteStudent")
     */
    public function delete($id){
        $data = $this->getDoctrine()->getRepository(Student::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();

        $this->addFlash('notice','Deleted Successfully');

        return $this->redirectToRoute('app_students');
    }
    
}
