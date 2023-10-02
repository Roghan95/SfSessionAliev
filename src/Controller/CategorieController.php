<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    // Fonction permettant d'afficher toutes les catégories
    #[Route('/categorie', name: 'app_categorie')] // URL
    public function index(CategorieRepository $categorieRepository): Response
    {
        if($this->getUser()) {
        // Récupère toutes les catégories dans l'ordre alphabétique des noms de catégorie
        $categories = $categorieRepository->findBy([], ['nomCategorie' => 'ASC']);
        
        // Renvoie la vue 'categorie/index.html.twig' avec les catégories
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    return $this->redirectToRoute('app_login');
    }
     // Fonction permettant d'ajouter un module et l'éditer
     #[Route('/categorie/new', name: 'new_categorie')] // URL
     #[Route('/categorie/{id}/edit', name: 'edit_categorie')] // URL
     public function new_edit(Categorie $categorie = null, Request $request = null, EntityManagerInterface $entityMananger) : Response {
         if($this->getUser()) {
         // Si il existe
         if(!$categorie) {
             // On instancie le manager
             $categorie = new Categorie();
         }
 
         // On récupère le form de l'entity moduleType
         $form = $this->createForm(CategorieType::class, $categorie);
 
         // on vérifie l'intégrité des données qu'on a reçu par rapport aux données attendus 
         $form->handleRequest($request);
 
         // Si il est soumis et valide
         if($form->isSubmitted() && $form->isValid()) {
             $categorie = $form->getData(); // on récupère le saisi du form
 
             $entityMananger->persist($categorie); // prepare PDO
             $entityMananger->flush(); // execute PDO
 
             // Redirection vers la page module
             return $this->redirectToRoute('app_categorie');
         }
 
         // Renvoie la vue 'module/new.html.twig' avec le form
         return $this->render('categorie/new.html.twig', [
             'formAddCategorie' => $form
         ]);
     }
     return $this->redirectToRoute('app_login');
     }
 
     // Fonction permettant de supprimer un module
     #[Route('/categorie/{id}/delete', name: 'delete_categorie')]
     public function delete(Categorie $categorie, EntityManagerInterface $entityMananger) {
         if($this->getUser()) {
         
         $entityMananger->remove($categorie); // prepare PDO
         $entityMananger->flush(); // execute PDO
 
         // Redirection vers la page module
         return $this->redirectToRoute('app_categorie');
         }
         return $this->redirectToRoute('app_login');
     }

    // Fonction permettant d'afficher le détail d'une catégorie
    #[Route('/categorie/{id}', name: 'show_categorie')] // URL
    public function showCategorie(Categorie $categorie) : Response {
        
        // Renvoie la vue 'categorie/show.html.twig' avec la catégorie
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie
        ]);
    }
}
