<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Form\FormateurType;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormateurController extends AbstractController
{
    // Fonction permettant d'afficher tous les formateurs
    #[Route('/formateur', name: 'app_formateur')] // URL
    public function index(FormateurRepository $formateurRepository): Response
    {
        if($this->getUser()) {

        // Récupère tous les formateurs dans l'ordre alphabétique des noms de formateur
        $formateurs = $formateurRepository->findBy([], ['nomFormateur' => 'ASC']);

        // Renvoie la vue 'formateur/index.html.twig' avec les formateurs
        return $this->render('formateur/index.html.twig', [
            'formateurs' => $formateurs,
        ]);
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/formateur/new', name: 'new_formateur')]
    #[Route('/formateur/{id}/edit', name: 'edit_formateur')]
    public function new_edit(Formateur $formateur = null, Request $request = null, EntityManagerInterface $entityMananger) : Response {
        if($this->getUser()) {
            // Si il existe
        if(!$formateur) {
            // On instancie le manager
            $formateur = new Formateur();
        }

        // On récupère le form de l'entity FormateurType
        $form = $this->createForm(FormateurType::class, $formateur);

        $form->handleRequest($request);

        // Si il est soumis et valide
        if($form->isSubmitted() && $form->isValid()) {
            $formateur = $form->getData();

            $entityMananger->persist($formateur); // prepare PDO
            $entityMananger->flush(); // execute PDO

            return $this->redirectToRoute('app_formateur');
        }
        // Renvoie la vue 'formateur/new.html.twig' avec le form
        return $this->render('formateur/new.html.twig', [
            'formAddFormateur' => $form
        ]);
    }
    return $this->redirectToRoute('app_login');
    }

    // Fonction permettant de supprimer un formateur
    #[Route('/formateur/{id}/delete', name: 'delete_formateur')] // URL
    public function delete(Formateur $formateur, EntityManagerInterface $entityMananger) {
        if($this->getUser()) {
        
        $entityMananger->remove($formateur); // prepare PDO
        $entityMananger->flush(); // execute PDO

        // Redirection vers la page formateur
        return $this->redirectToRoute('app_formateur');
        }

    return $this->redirectToRoute('app_login');
    }

    // Fonction permettant d'afficher un formateur
    #[Route('/formateur/{id}', name: 'show_formateur')] // URL
    public function showFormateur(Formateur $formateur) : Response {
        if ($this->getUser()) {
        
        // Renvoie la vue 'formateur/show.html.twig' avec le formateur
        return $this->render('formateur/show.html.twig', [
            'formateur' => $formateur
        ]);
    }
    return $this->redirectToRoute('app_login');
    }
}
