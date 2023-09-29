<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    // Fonction permettant d'afficher toutes les catégories
    #[Route('/categorie', name: 'app_categorie')] // URL
    public function index(CategorieRepository $categorieRepository): Response
    {
        // Récupère toutes les catégories dans l'ordre alphabétique des noms de catégorie
        $categories = $categorieRepository->findBy([], ['nomCategorie' => 'ASC']);
        
        // Renvoie la vue 'categorie/index.html.twig' avec les catégories
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    // Fonction permettant d'afficher une catégorie
    #[Route('/categorie/{id}', name: 'show_categorie')] // URL
    public function showCategorie(Categorie $categorie) : Response {
        
        // Renvoie la vue 'categorie/show.html.twig' avec la catégorie
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie
        ]);
    }
}
