<?php

namespace App\Controller;
use App\Entity\Student;
use App\Form\StudentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Gedmo\Sluggable\Util\Urlizer;

class StudentsController extends AbstractController
{
    /**
     * @Route("/students", name="app_students")
     */
    public function index(): Response
    {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        return $this->render('students/index.html.twig', [
            'students' => $students
        ]);
    }
    /**
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
     * @Route("/students/create", name="createStudent")
     */
    public function create(Request $request){

        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $uploadedFile = $form['img']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $student->setImage($newFilename);
            }

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
     * @Route("/students/update/{id}", name="updateStudent")
     */
    public function update(Request $request, $id){

        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        $form = $this -> createForm(StudentType::class, $student);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $uploadedFile = $form['img']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $student->setImage($newFilename);
            }

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
