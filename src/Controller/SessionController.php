<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
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
        ]);
    }

    #[Route('/session/{id}', name: 'show_session')]
    public function showSession(Session $session, SessionRepository $sessionRepository, EntityManagerInterface $entityManager) : Response {

        return $this->render('session/show.html.twig', [
            'session' => $session
        ]);
    }
}
