<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Formateur;
use App\Repository\SessionRepository;
use App\Repository\FormateurRepository;
use App\Repository\ProgrammeRepository;
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
    

    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session, SessionRepository $sessionRepository): Response
    {
        // Récupère la session spécifique en fonction de l'ID passé dans l'URL
        // Récupère les stagiaires qui ne sont pas inscrits à cette session
        $stagiairesNotIn = $sessionRepository->findStagiaireNotIn($session->getId());
    
        // Récupère les modules qui ne sont pas programmés pour cette session
        $modulesNotIn = $sessionRepository->findNonProgrammer($session->getId());
    
        // Renvoie la vue 'session/show.html.twig' avec les détails de la session, les stagiaires non inscrits
        // et les modules non programmés
        return $this->render('session/show.html.twig', [
            'session' => $session,
            'stagiairesNotIn' => $stagiairesNotIn,
            'modulesNotIn' => $modulesNotIn
        ]);
    }
    
}
