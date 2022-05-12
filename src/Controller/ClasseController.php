<?php

namespace App\Controller;
use App\Entity\Classe;
use App\Form\ClasseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ClasseController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/classes", name="app_classe")
     */
    public function index(Request $request): Response
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

        $classes = $this->getDoctrine()->getRepository(Classe::class)->findAll();

        if($form->isSubmitted()){

            $type = $form->getData()['type'];
            $data = $form->getData()['query'];

            if($data != null){
                $em = $this->getDoctrine()->getManager();
                $query = $em->createQuery(
                    'SELECT c
                    FROM App:Classe c
                    WHERE c.'.$type.' = :data'
                )
                ->setParameter('data', $data);    

                $classes = $query->getResult();        
            }
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
        $data = $this->getDoctrine()->getRepository(Classe::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();

        $this->addFlash('notice','Deleted Successfully');

        return $this->redirectToRoute('app_classe');
    }
    
}
