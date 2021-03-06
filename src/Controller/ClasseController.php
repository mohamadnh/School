<?php

namespace App\Controller;
use App\Entity\Classe;
use App\Form\ClasseType;
use App\Repository\ClasseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Knp\Component\Pager\PaginatorInterface;

class ClasseController extends AbstractController
{

    public function __construct(private ClasseRepository $classeRepository)
    {
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/classes", name="app_classe")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $form = $this->createFormBuilder(null)
        ->add('type', ChoiceType::class, [
            'choices' => [
                'name'=>'name',
                'section'=>'section'
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

            $classes = $this->classeRepository->getFilterResult($type, $data);

        } else {
            $classes = $this->getDoctrine()->getRepository(Classe::class)->findAll();
        }

        $classes = $paginator->paginate(
            $classes, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            2/*limit per page*/
        );

        if($form->isSubmitted()){

            $data = $form->getData()['query'];
            $type = $form->getData()['type'];

            $route = $this->generateUrl('app_classe', ['query' => $data, 'type' => $type]);
            
            return $this->redirect($route);
        }

        
        return $this->render('classe/index.html.twig', [
            'classes' => $classes,
            'form' => $form->createView()
        ]);
    }
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/classes/detail/{id}", name="classeDetail")
     */
    public function detail($id): Response
    {
        $classe = $this->getDoctrine()->getRepository(Classe::class)->find($id);

        return $this->render('classe/detail.html.twig', [
            'classe' => $classe
        ]);
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/classes/create", name="createClasse")
     */
    public function create(Request $request){

        $classe = new Classe();
        $form = $this->createForm(ClasseType::class, $classe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($classe);
            $em->flush();

            $this->addFlash('notice','Submitted Successfully');

            return $this->redirectToRoute('app_classe');
        }

        return $this->render('classe/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/classes/update/{id}", name="updateClasse")
     */
    public function update(Request $request, $id){

        $classe = $this->getDoctrine()->getRepository(Classe::class)->find($id);
        $form = $this -> createForm(ClasseType::class, $classe);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($classe);
            $em->flush();

            $this->addFlash('notice','Updated Successfully');

            return $this->redirectToRoute('app_classe');
        }

        return $this->render('classe/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/classes/delete/{id}", name="deleteClasse")
     */
    public function delete($id){

        $this->classeRepository->delete($id);
        $this->addFlash('notice','Deleted Successfully');

        return $this->redirectToRoute('app_classe');
    }
    
}
