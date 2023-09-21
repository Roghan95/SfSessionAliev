<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Repository\FormateurRepository;
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

    #[Route('/formateur/{id}', name: 'show_formateur')]
    public function showFormateur(Formateur $formateur) : Response {
        
        return $this->render('formateur/show.html.twig', [
            'formateur' => $formateur
        ]);
    }
}
