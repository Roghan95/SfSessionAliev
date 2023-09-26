<?php

namespace App\Controller;

use App\Entity\Module;
use App\Repository\ModuleRepository;
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

    #[Route('/module/{id}', name: 'show_module')]
    public function show(Module $module, ModuleRepository $moduleRepository ) : Response {

        // $modulesNotIn = $moduleRepository->findNonProgrammer($module->getId());
        return $this->render('session/show.html.twig', [
            'module' => $module
            // 'modulesNotIn' => $modulesNotIn
        ]);
    }
}
