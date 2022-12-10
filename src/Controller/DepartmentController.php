<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DepartmentRepository;
use App\Entity\Department;

class DepartmentController extends AbstractController
{
    #[Route('/department', name: 'department_index')]
    public function index(DepartmentRepository $repository): Response
    {
        //dump($repository);die;
        $departments = $repository->findAll();

        return $this->render('department/index.html.twig', [
            'departments' => $departments,
        ]);
    }

    #[Route('/department/{id}', name: 'department_show')]
    public function show(Department $department): Response
    {
        //dd($department->getActualManager());  //TODO Heavy Model, Light Controller
        
        $managers = $department->getManagers();
        $actualManager = null;

        foreach($managers as $manager) {
            $stories = $manager->getManagingStories();

            foreach($stories as $story) {
                //dump($story);

                if($story->getToDate()->format('Y')=='9999') {
                    $actualManager = $manager;
                    break;
                }
            }
        }

        return $this->render('department/show.html.twig', [
            'department' => $department,
            'manager' => $actualManager,
        ]);
    }
}
