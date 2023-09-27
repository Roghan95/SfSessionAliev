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
    #[Route('/formateur', name: 'app_formateur')]
    public function index(FormateurRepository $formateurRepository): Response
    {

        $formateurs = $formateurRepository->findBy([], ['nomFormateur' => 'ASC']);
        return $this->render('formateur/index.html.twig', [
            'formateurs' => $formateurs,
        ]);
    }

    #[Route('/formateur/new', name: 'new_formateur')]
    #[Route('/formateur/{id}/edit', name: 'edit_formateur')]
    public function new_edit(Formateur $formateur = null, Request $request = null, EntityManagerInterface $entityMananger) : Response {
        
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

        return $this->render('formateur/new.html.twig', [
            'formAddFormateur' => $form
        ]);
    }

    #[Route('/formateur/{id}/delete', name: 'delete_formateur')]
    public function delete(Formateur $formateur, EntityManagerInterface $entityMananger) {
        
        $entityMananger->remove($formateur); // prepare PDO
        $entityMananger->flush(); // execute PDO

        return $this->redirectToRoute('app_formateur');
    }

    #[Route('/formateur/{id}', name: 'show_formateur')]
    public function showFormateur(Formateur $formateur) : Response {
        
        return $this->render('formateur/show.html.twig', [
            'formateur' => $formateur
        ]);
    }
}
