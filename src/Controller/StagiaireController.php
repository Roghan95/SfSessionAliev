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
    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(StagiaireRepository $stagiaireRepository): Response
    {
        $stagiaires = $stagiaireRepository->findBy([], ['nomStagiaire' => 'ASC']);
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }

    #[Route('/stagiaire/new', name: 'new_stagiaire')]
    #[Route('/stagiaire/{id}/edit', name: 'edit_stagiaire')]
    public function new_edit(Stagiaire $stagiaire = null, Request $request = null, EntityManagerInterface $entityMananger) : Response {
        
        // Si il existe
        if(!$stagiaire) {
            // On instancie le manager
            $stagiaire = new Stagiaire();
        }

        // On récupère le form de l'entity stagiaireType
        $form = $this->createForm(StagiaireType::class, $stagiaire);

        $form->handleRequest($request);

        // Si il est soumis et valide
        if($form->isSubmitted() && $form->isValid()) {
            $stagiaire = $form->getData();

            $entityMananger->persist($stagiaire); // prepare PDO
            $entityMananger->flush(); // execute PDO

            return $this->redirectToRoute('app_stagiaire');
        }

        return $this->render('stagiaire/new.html.twig', [
            'formAddstagiaire' => $form
        ]);
    }

    #[Route('/stagiaire/{id}/delete', name: 'delete_stagiaire')]
    public function delete(Stagiaire $stagiaire, EntityManagerInterface $entityMananger) {
        
        $entityMananger->remove($stagiaire); // prepare PDO
        $entityMananger->flush(); // execute PDO

        return $this->redirectToRoute('app_stagiaire');
    }

    #[Route('/stagiaire/{id}', name: 'show_stagiaire')]
    public function showStagiaire(Stagiaire $stagiaire, Session $sessions) : Response {
        
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire,
            'sessions' => $sessions
        ]);
    }
}
