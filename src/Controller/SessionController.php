<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Session;
use App\Entity\Formateur;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')] // URL
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

    #[Route('/session/new', name: 'new_session')] // URL
    #[Route('/session/{id}/edit', name: 'edit_session')] // URL
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

            // Redirection vers la page session
            return $this->redirectToRoute('app_session');
        }

        // Renvoie la vue 'session/new.html.twig' avec le form
        return $this->render('session/new.html.twig', [
            'formAddSession' => $form
        ]);
    }

    // Fonction permettant de supprimer un session
    #[Route('/session/{id}/delete', name: 'delete_session')] // URL
    public function delete(Session $session, EntityManagerInterface $entityMananger) {
        
        $entityMananger->remove($session); // prepare PDO
        $entityMananger->flush(); // execute PDO

        // Redirection vers la page session
        return $this->redirectToRoute('app_session');
    }


    // Méthode qui permet de déprogrammer un formateur
    #[Route('/session/{session}/{stagiaire}/delete', name: 'delete_stagiaire_session')] // URL
    public function deleteStagiaire(Session $session, Stagiaire $stagiaire, EntityManagerInterface $entityMananger) {
        
        $session->removeStagiaire($stagiaire); // On supprime le formateur de la session
        $entityMananger->flush(); // On execute la requête

        // Redirection vers la page session
        return $this->redirectToRoute("show_session", ["id" => $session->getId()]);
    }

    // Méthode qui permet d'ajouter un formateur
    #[Route('/session/{session}/{stagiaire}/add', name: 'add_stagiaire_session')] // URL
    public function addStagiaire(Session $session, Stagiaire $stagiaire, EntityManagerInterface $entityMananger) {

        
        $session->addStagiaire($stagiaire); // On ajoute le formateur dans la session
        $entityMananger->flush(); // On execute la requête

        // Redirection vers la page session
        return $this->redirectToRoute("show_session", ["id" => $session->getId()]);
    }

    // Méthode qui permet de déprogrammer un programme
    #[Route('/programme/{programme}/delete', name: 'delete_programme_session')]
    public function deleteProgramme(Programme $programme, EntityManagerInterface $entityMananger) {

        $session = $programme->getSession(); // On récupère la session du programme
        $entityMananger->remove($programme); // On supprime le programme
        $entityMananger->flush(); // On execute la requête

        // Redirection vers la page session
        return $this->redirectToRoute("show_session", ["id" => $session->getId()]);
    }

    #[Route('/programme/{session}/{module}/add', name: 'add_programme_session')] // URL
    public function addProgramme(Request $request, Session $session, Module $module, EntityManagerInterface $entityMananger) {
        // On instancie l'entity Programme
        $programme = new Programme();

        // On attribue module a programme
        $programme->setModule($module);

        // On attribue session a programme
        $programme->setSession($session);

        // On récupère ce que l'user a entrer dans le champs nbJours (method POST = request->request)
        $nbJours = $request->request->get("nbJours");

        // on attribue le nbJours a programme
        $programme->setNbJours($nbJours);
        
        // On ajoute le programme dans la session
        $session->addProgramme($programme);

        // On prepare
        $entityMananger->persist($programme);
        // et on exécute
        $entityMananger->flush();

        // On return la vue show_session et lui donne l'id de la session actuel
        return $this->redirectToRoute("show_session", ["id" => $session->getId()]);
    }

    // Méthode qui permet de déprogrammer un formateur
    #[Route('/session/{id}', name: 'show_session')] // URL
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
