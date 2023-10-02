<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    // Fonction permettant d'afficher tous les stagiaires
    #[Route('/stagiaire', name: 'app_stagiaire')] // URL
    public function index(StagiaireRepository $stagiaireRepository): Response
    {
        if($this->getUser()) {
        // Récupère tous les stagiaires dans l'ordre alphabétique des noms de stagiaire
        $stagiaires = $stagiaireRepository->findBy([], ['nomStagiaire' => 'ASC']);

        // Renvoie la vue 'stagiaire/index.html.twig' avec les stagiaires
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }
    return $this->redirectToRoute('app_login');
    }

    // Fonction permettant d'ajouter un stagière et éditer par l'id du stagiaire
    #[Route('/stagiaire/new', name: 'new_stagiaire')] // URL
    #[Route('/stagiaire/{id}/edit', name: 'edit_stagiaire')] // URL
    public function new_edit(Stagiaire $stagiaire = null, Request $request = null, EntityManagerInterface $entityMananger) : Response {
        if ($this->getUser()) {
            // Si il existe
            if(!$stagiaire) {
                // On instancie le manager
                $stagiaire = new Stagiaire();
            }
            // On récupère le form de l'entity stagiaireType
            $form = $this->createForm(StagiaireType::class, $stagiaire);
            // on vérifie l'intégrité des données qu'on a reçu par rapport aux données attendus 
            $form->handleRequest($request);
            // Si il est soumis et valide
            if($form->isSubmitted() && $form->isValid()) {
                $stagiaire = $form->getData(); // Sauvegarde les données du form dans $stagiaire
                $entityMananger->persist($stagiaire); // prepare PDO
                $entityMananger->flush(); // execute PDO
                // Redirection vers la page stagiaire
                return $this->redirectToRoute('app_stagiaire');
            }
            // Renvoie la vue 'stagiaire/new.html.twig' avec le form
            return $this->render('stagiaire/new.html.twig', [
                'formAddStagiaire' => $form
            ]);
        }
        return $this->redirectToRoute('app_login');
    }

    // Fonction permettant de supprimer un stagiaire
    #[Route('/stagiaire/{id}/delete', name: 'delete_stagiaire')] // URL
    public function delete(Stagiaire $stagiaire, EntityManagerInterface $entityMananger) {
        if($this->getUser()) {
        $entityMananger->remove($stagiaire); // prepare PDO
        $entityMananger->flush(); // execute PDO
        // Redirection vers la page stagiaire
        return $this->redirectToRoute('app_stagiaire');
        }
        return $this->redirectToRoute('app_login');
    }
    // Fonction permettant d'afficher les détails d'un stagiaire
    #[Route('/stagiaire/{id}', name: 'show_stagiaire')] // URL
    public function showStagiaire(Stagiaire $stagiaire, Session $sessions) : Response {
        if($this->getUser()) {
        
        // Récupère toutes les sessions dans l'ordre alphabétique des noms de session
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire,
            'sessions' => $sessions
        ]);
    }
    return $this->redirectToRoute('app_login');
    }
}
