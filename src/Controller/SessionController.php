<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Formateur;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use App\Repository\FormateurRepository;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(SessionRepository $sessionRepository, ProgrammeRepository $programmeRepository): Response
    {
        // Récupère toutes les sessions dans l'ordre alphabétique des noms de session
        $sessions = $sessionRepository->findBy([], ['nomSession' => 'ASC']);
    
        // Récupère tous les programmes
        $programmes = $programmeRepository->findAll();
    
        // Renvoie la vue 'session/index.html.twig' avec les sessions et les programmes
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
            'programmes' => $programmes
        ]);
    }

    #[Route('/session/new', name: 'new_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function new_edit(Session $session = null, Request $request = null, EntityManagerInterface $entityMananger) : Response {
        
        // Si il existe
        if(!$session) {
            // On instancie le manager
            $session = new Session();
        }

        // On récupère le form de l'entity sessionType
        $form = $this->createForm(SessionType::class, $session);

        // on vérifie l'intégrité des données qu'on a reçu par rapport aux données attendus 
        $form->handleRequest($request);

        // Si il est soumis et valide
        if($form->isSubmitted() && $form->isValid()) {
            $session = $form->getData(); // on récupère le saisi du form

            $entityMananger->persist($session); // prepare PDO
            $entityMananger->flush(); // execute PDO

            return $this->redirectToRoute('app_session');
        }

        return $this->render('session/new.html.twig', [
            'formAddSession' => $form
        ]);
    }

    // Fonction permettant de supprimer un session
    #[Route('/session/{id}/delete', name: 'delete_session')]
    public function delete(Session $session, EntityManagerInterface $entityMananger) {
        
        $entityMananger->remove($session); // prepare PDO
        $entityMananger->flush(); // execute PDO

        return $this->redirectToRoute('app_session');
    }


    

    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session, SessionRepository $sessionRepository): Response
    {
        // Récupère la session spécifique en fonction de l'ID passé dans l'URL
        // Récupère les stagiaires qui ne sont pas inscrits à cette session
        $stagiairesNotIn = $sessionRepository->findStagiaireNotIn($session->getId());
    
        // Récupère les sessions qui ne sont pas programmés pour cette session
        $sessionsNotIn = $sessionRepository->findNonProgrammer($session->getId());
    
        // Renvoie la vue 'session/show.html.twig' avec les détails de la session, les stagiaires non inscrits
        // et les sessions non programmés
        return $this->render('session/show.html.twig', [
            'session' => $session,
            'stagiairesNotIn' => $stagiairesNotIn,
            'sessionsNotIn' => $sessionsNotIn
        ]);
    }
    
}
