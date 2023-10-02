<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
    // Fonction permettant d'afficher toutes les formations
    #[Route('/formation', name: 'app_formation')] // URL
    public function index(FormationRepository $formationRepository): Response
    {
        if($this->getUser()) {
        // Récupère toutes les formations dans l'ordre alphabétique des noms de formation
        $formations = $formationRepository->findBy([], ['nomFormation' => 'ASC']);

        // Renvoie la vue 'formation/index.html.twig' avec les formations
        return $this->render('formation/index.html.twig', [
            'formations' => $formations
        ]);
    }
    return $this->redirectToRoute('app_login');
    }

    // Fonction permettant d'ajouter une formation et l'éditer
    #[Route('/formation/add', name: 'add_formation')] // URL
    #[Route('/formation/{id}/edit', name: 'edit_formation')] // URL
    public function addEditFormation(Formation $formation = null, EntityManagerInterface $entityManager, Request $request): Response
    {
        if($this->getUser()) {
        // Si il existe
        if (!$formation) {
            $formation = new Formation();
        }
        // On récupère le form de l'entity formationType
        $form = $this->createForm(FormationType::class, $formation);
        // on vérifie l'intégrité des données qu'on a reçu par rapport aux données attendus
        $form->handleRequest($request);

        // Si il est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($formation); // prepare PDO
            $entityManager->flush(); // execute PDO

            return $this->redirectToRoute('app_formation'); // Redirection vers la page formation
        }

        // Renvoie la vue 'formation/new.html.twig' avec le form
        return $this->render('formation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    return $this->redirectToRoute('app_login');
    }


    // Fonction permettant de supprimer une formation
    #[Route('/formation/{id}/delete', name: 'delete_formation')]
    public function deleteFormation(Formation $formation, EntityManagerInterface $entityManager): Response
    {
        if($this->getUser()) {
        // On supprime la formation
        $entityManager->remove($formation);
        // On execute la requête
        $entityManager->flush();

        // Redirection vers la page formation
        return $this->redirectToRoute('app_formation');
        }
        return $this->redirectToRoute('app_login');
    }

    // Fonction permettant d'afficher les détails d'une formation
    #[Route('/formation/{id}', name: 'show_formation')]
    public function showSession(Formation $formation, FormationRepository $formationRepository, EntityManagerInterface $entityManager) : Response {
        if($this->getUser()) {

        // Récupère toutes les formations dans l'ordre alphabétique des noms de formation
        return $this->render('formation/show.html.twig', [
            'formation' => $formation
        ]);
    }
    return $this->redirectToRoute('app_login');
    }
}
