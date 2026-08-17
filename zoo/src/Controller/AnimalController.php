<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
// Va me servir à récupérer des informations en base de donnée
use App\Repository\AnimalRepository;

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
}
