<?php

namespace App\Controller;
use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Knp\Component\Pager\PaginatorInterface;

class StudentsController extends AbstractController
{
    public function __construct(private StudentRepository $repository)
    {
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/students", name="app_students")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_USER');
        
        $form = $this->createFormBuilder(null)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'firstName'=>'firstName',
                    'lastName'=>'lastName',
                    'classe' => 'classe'
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
            $students = $this->repository->getFilterResult($type,$data);

        } else {
            $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        }

        $students = $paginator->paginate(
            $students, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            2/*limit per page*/
        );

        if($form->isSubmitted()){

            $data = $form->getData()['query'];
            $type = $form->getData()['type'];

            $route = $this->generateUrl('app_students', ['query' => $data, 'type' => $type]);

            return $this->redirect($route);
        }
        return $this->render('students/index.html.twig', [
            'students' => $students,
            'form' => $form->createView()
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

    // public function searchBarAction(){

    //     $form = $this->createFormBuilder(null)
    //     ->setAction($this->generateUrl('handleSearch'))
    //     ->add('query', TextType::class, [
    //         'required' => false,
    //         'attr' => [
    //             'class' => 'form-control'
    //         ]
    //     ])
    //     ->add('search', SubmitType::class, [
    //         'attr' => [
    //             'class' => 'btn btn-primary'
    //         ]
    //     ])->getForm();
    //     return $this->render('students/search.html.twig', [
    //         'form' => $form->createView()
    //     ]);
    // }

    // /**
    //  *
    //  * @Route("/students/reset", name="reset")
    //  */
    // public function handleSearch(Request $request){
    //     $data = $request->request->get('form')['query'];

    //     if($data != null){
    //         $em = $this->getDoctrine()->getManager();

    //         $query = $em->createQuery(
    //             'SELECT s
    //             FROM App:Student s
    //             WHERE s.firstName = :firstName'
    //         )->setParameter('firstName', $data);   

    //         $students = $query->getResult();

    //         return $this->render('students/index.html.twig', [
    //             'students' => $students,
    //         ]);
    //     }
    //     return $this->redirectToRoute('app_students');
    // }
    
}
