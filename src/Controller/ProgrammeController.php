<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgrammeController extends AbstractController
{
    // Fonction permettant d'afficher tous les programmes
    // #[Route('/programme', name: 'app_programme')]
    // public function index(): Response
    // {
    //     return $this->render('programme/index.html.twig', [
    //         'controller_name' => 'ProgrammeController',
    //     ]);
    // }
}
