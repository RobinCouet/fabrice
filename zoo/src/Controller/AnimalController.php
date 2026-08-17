<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
// Va me servir à récupérer des informations en base de donnée
use App\Repository\AnimalRepository;
// On recupère le model / entity
use App\Entity\Animal;
use App\Form\AnimalType;
// Lui nous permet de sauvegarder, modifier, supprimer des infos en DB
use Doctrine\ORM\EntityManagerInterface;

final class AnimalController extends AbstractController
{
    #[Route('/animal', name: 'app_animal')]
    public function index(AnimalRepository $repository): Response
    {
        // https://symfony.com/doc/current/doctrine.html#fetching-objects-from-the-database
        // ->findAll() permet de récuperer toutes les données d'une table
        $animals = $repository->findAll();

        return $this->render('animal/index.html.twig', [
            'animals' => $animals
        ]);
    }

    #[Route("/animal/new", name: "app_animal_new")]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $animal = new Animal;
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($animal);
            $em->flush();

            return $this->redirectToRoute("app_animal_show", ['id' => $animal->getId()]);
        }


        return $this->render('animal/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route("/animal/{id}/edit", name: "app_animal_edit")]
    public function edit(Request $request, EntityManagerInterface $em, Animal $animal): Response
    {
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($animal);
            $em->flush();

            return $this->redirectToRoute("app_animal_show", ['id' => $animal->getId()]);
        }


        return $this->render('animal/edit.html.twig', [
            'form' => $form,
            'animal' => $animal
        ]);
    }

    #[Route("/animal/{id}/delete", name: "app_animal_delete")]
    public function delete(Animal $animal, EntityManagerInterface $em): Response
    {
        if ($animal) {
            $em->remove($animal);
            $em->flush();
        }

        return $this->redirectToRoute("app_animal");
    }

    #[Route("/animal/{id}", name: "app_animal_show")]
    public function show(Animal $animal): Response
    {
        if (!$animal) {
            return $this->redirectToRoute('app_animal');
        }

        return $this->render('animal/show.html.twig', [
            'animal' => $animal
        ]);
    }
}
