<?php

namespace App\Controller;
use App\Entity\Course;
use App\Entity\StudentsGrades;
use App\Form\StudentsGradesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class StudentsGradesController extends AbstractController
{
    /**
     * @Route("/students/grades", name="app_students_grades")
     */
    public function index(): Response
    {
        $studentsGrades = $this->getDoctrine()->getRepository(StudentsGrades::class)->findAll();
        return $this->render('students_grades/index.html.twig', [
            'studentsGrades' => $studentsGrades,
        ]);
    }

    /**
     * @Route("/students/grades/detail/{id}", name="studentsGradeDetail")
     */
    public function detail($id): Response
    {
        $studentsGrade = $this->getDoctrine()->getRepository(StudentsGrades::class)->find($id);

        return $this->render('students_grades/detail.html.twig', [
            'studentsGrade' => $studentsGrade
        ]);
    }
    /**
     * @Route("/students/grades/create", name="createStudentsGrade")
     */
    public function create(Request $request){

        $studentsGrades = new StudentsGrades();
        $form = $this->createForm(StudentsGradesType::class, $studentsGrades);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $course = $form['course']->getData();
            $studentsGrades->setCourse($course);

            $studentsGrades->setClasse($course->getClasse());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($studentsGrades);
            $em->flush();

            $this->addFlash('notice','Submitted Successfully');

            return $this->redirectToRoute('app_students_grades');
        }

        return $this->render('students_grades/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/students/grades/delete/{id}", name="deleteStudentsGrade")
     */
    public function delete($id){
        $data = $this->getDoctrine()->getRepository(StudentsGrades::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();

        $this->addFlash('notice','Deleted Successfully');

        return $this->redirectToRoute('app_students_grades');
    }

}
