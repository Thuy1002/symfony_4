<?php

namespace App\Controller;

use App\Entity\Animal;

//use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AnnimalController extends AbstractController
{
    /**
     * @Route("/", name="app_annimal")
     */
    public function index(Request $request): Response
    {
//        $animal = $this->getDoctrine()->getRepository(Animal::class)->findAll();
//        return $this->render('animal/index.html.twig', [
//            'An' => $animal,
//        ]);
        $search = $request->query->get('search');
        if ($search) {
            $animal = $this->getDoctrine()->getRepository(Animal::class)->search($search);
        } else {
            $animal = $this->getDoctrine()->getRepository(Animal::class)->findAll();
        }
        return $this->render('animal/index.html.twig', array('An' => $animal));
    }


    /** @Route("/search", name="search")
     * Method({"Get"})
     */


////      $search = Animal::where('name', 'like', '%' . $name . '%')->get();
//        $search = $this->getDoctrine()->getRepository(Animal::class)->search();


//    /**
//     * @Route("/annimal/{id}", name="show_animal")
//     *
//     */
//    public function show($id): Response
//    {
//        $animal = $this->getDoctrine()->getRepository(Animal::class)->find($id);
//        return $this->render('animal/show.html.twig', ['detail' => $animal]);
//    }

    /**
     * @Route("/add",name="animal_add")
     * Method({"GET", "POST"})
     */
    public function add(Request $request)
    {
        $Animal = new Animal();
        $form = $this->createFormBuilder($Animal)
            ->add('name', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('weight', NumberType::class, array('required' => FALSE, 'attr' => array('class' => 'form-control')))
            ->add('color', TextareaType::class, array('required' => FALSE, 'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Animal = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Animal);
            $entityManager->flush();
            return $this->redirectToRoute('app_annimal');
        }
        return $this->render('animal/add.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/animal/edit/{id}", name="edit_article")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id)
    {
        $animal = new Animal();
        $animal = $this->getDoctrine()->getRepository(Animal::class)->find($id);

        $form = $this->createFormBuilder($animal)
            ->add('name', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('weight', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control')
            ))
            ->add('color', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control')
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Update',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_annimal');
        }

        return $this->render('animal/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/animal/delete/{id}")
     * Method({"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $animal = $this->getDoctrine()->getRepository(Animal::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($animal);
        $entityManager->flush();
        return $this->redirectToRoute('app_annimal');


    }


}
