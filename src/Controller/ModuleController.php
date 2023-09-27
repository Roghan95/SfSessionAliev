<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Repository\ModuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    #[Route('/module', name: 'app_module')]
    public function index(ModuleRepository $moduleRepository): Response
    {
        $modules = $moduleRepository->findBy([], ['nomModule' => 'ASC']);
        return $this->render('module/index.html.twig', [
            'modules' => $modules
        ]);
    }

    #[Route('/module/new', name: 'new_module')]
    #[Route('/module/{id}/edit', name: 'edit_module')]
    public function new_edit(Module $module = null, Request $request = null, EntityManagerInterface $entityMananger) : Response {
        
        // Si il existe
        if(!$module) {
            // On instancie le manager
            $module = new Module();
        }

        // On récupère le form de l'entity moduleType
        $form = $this->createForm(ModuleType::class, $module);

        // on vérifie l'intégrité des données qu'on a reçu par rapport aux données attendus 
        $form->handleRequest($request);

        // Si il est soumis et valide
        if($form->isSubmitted() && $form->isValid()) {
            $module = $form->getData(); // on récupère le saisi du form

            $entityMananger->persist($module); // prepare PDO
            $entityMananger->flush(); // execute PDO

            return $this->redirectToRoute('app_module');
        }

        return $this->render('module/new.html.twig', [
            'formAddModule' => $form
        ]);
    }

    // Fonction permettant de supprimer un module
    #[Route('/module/{id}/delete', name: 'delete_module')]
    public function delete(Module $module, EntityManagerInterface $entityMananger) {
        
        $entityMananger->remove($module); // prepare PDO
        $entityMananger->flush(); // execute PDO

        return $this->redirectToRoute('app_module');
    }

    #[Route('/module/{id}', name: 'show_module')]
    public function show(Module $module) : Response {

        return $this->render('session/show.html.twig', [
            'module' => $module
        ]);
    }
}
