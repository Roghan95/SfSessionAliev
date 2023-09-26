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
        $sessions = $sessionRepository->findBy([], ['nomSession' => 'ASC']);
        $programmes = $programmeRepository->findAll();
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
            'programmes' => $programmes
        ]);
    }

    #[Route('/session/{id}', name: 'show_session')]
    public function show(Session $session, SessionRepository $sessionRepository) : Response {

        $stagiairesNotIn = $sessionRepository->findStagiaireNotIn($session->getId());
        $modulesNotIn = $sessionRepository->findNonProgrammer($session->getId());
        return $this->render('session/show.html.twig', [
            'session' => $session,
            'stagiairesNotIn' => $stagiairesNotIn,
            'modulesNotIn' => $modulesNotIn
        ]);
    }
}
