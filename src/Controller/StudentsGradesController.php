<?php

namespace App\Controller;
use App\Entity\Course;
use App\Entity\StudentsGrades;
use App\Form\StudentsGradesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Knp\Component\Pager\PaginatorInterface;
    
class StudentsGradesController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/students/grades", name="app_students_grades")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $form = $this->createFormBuilder(null)
        ->add('type', ChoiceType::class, [
            'choices' => [
                'firstName'=>'firstName',
                'lastName'=>'lastName',
                'classe' => 'classe',
                'course' => 'course',
                'grade' => 'grade'
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

        $data = $request->query->get('query');
        $type =$request->query->get('type');

        if($data != null && $type != null){

            $em = $this->getDoctrine()->getManager();
            if($type == 'classe'){
                $query = $em->createQuery(
                    'SELECT sg
                    FROM App:Student s, App:Classe c, App:StudentsGrades sg
                    WHERE s.classe = c.id and c.name = :data and sg.student = s.id'
                )
                ->setParameter('data', $data);
            }else if($type == 'course'){
                $query = $em->createQuery(
                    'SELECT sg
                    FROM App:Course c, App:StudentsGrades sg
                    WHERE sg.course = c.id and c.name = :data'
                )
                ->setParameter('data', $data);
            } else if($type == 'grade'){
                $query = $em->createQuery(
                    'SELECT sg
                    FROM App:StudentsGrades sg, App:Student s
                    WHERE sg.grade = :data and s.id = sg.student'
                )
                ->setParameter('data', $data);   
            } else {
                $query = $em->createQuery(
                    'SELECT sg
                    FROM App:StudentsGrades sg, App:Student s
                    WHERE s.'.$type.' = :data and s.id = sg.student'
                )
                ->setParameter('data', $data);    
            }
            $studentsGrades = $query->getResult();
        } else {
            $studentsGrades = $this->getDoctrine()->getRepository(StudentsGrades::class)->findAll();
        }

        $studentsGrades = $paginator->paginate(
            $studentsGrades, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            2/*limit per page*/
        );

        if($form->isSubmitted()){

            $data = $form->getData()['query'];
            $type = $form->getData()['type'];

            $route = $this->generateUrl('app_students_grades', ['query' => $data, 'type' => $type]);

            return $this->redirect($route);
        }

        return $this->render('students_grades/index.html.twig', [
            'studentsGrades' => $studentsGrades,
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
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
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
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
