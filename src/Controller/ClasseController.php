<?php

namespace App\Controller;
use App\Entity\Classe;
use App\Form\ClasseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClasseController extends AbstractController
{
    /**
     * @Route("/classes", name="app_classe")
     */
    public function index(): Response
    {
        $classes = $this->getDoctrine()->getRepository(Classe::class)->findAll();
        return $this->render('classe/index.html.twig', [
            'classes' => $classes
        ]);
    }
    /**
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
