<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\DepartmentRepository;
use App\Repository\DeptEmpRepository;
use App\Entity\DeptEmp;
use Doctrine\Persistence\ManagerRegistry;



#[Route('/employee')]
class EmployeeController extends AbstractController
{
    #[Route('/', name: 'app_employee_index', methods: ['GET'])]
    public function index(EmployeeRepository $employeeRepository): Response
    {
        return $this->render('employee/index.html.twig', [
            'employees' => $employeeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_employee_new', methods: ['GET', 'POST'])]
    public function new(Request $request, 
        EmployeeRepository $employeeRepository, 
        DeptEmpRepository $deptEmpRepository, 
        DepartmentRepository $deptRepository, 
        UserPasswordHasherInterface $passwordHasher,
        ManagerRegistry $doctrine): Response
    {
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword(
                $employee,
                $employee->getPassword()
            );
            $employee->setPassword($hashedPassword);

            $employee->setRoles(['ROLE_USER']);

            $employeeRepository->save($employee, true);
            
            //$employee = $employeeRepository->findOneBy([],['id'=>'DESC']);
dump($employee);
            $deptEmp = new DeptEmp();
            $deptEmp->setEmployee($employee);
            $deptEmp->setDepartment($deptRepository->find('d001'));
            $deptEmp->setFromDate(new \DateTime('now'));
            $deptEmp->setToDate(new \DateTime('9999-12-31'));
            $deptEmp->setEmployeeFull($employee);
dump($deptEmp);
            try {
                $manager = $doctrine->getManager();
                $manager->persist($deptEmp);
                $manager->flush();
                dd($deptEmp);
            } catch( ForeignKeyConstraintViolationException $e) {
                dd($e);

            }


            return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('employee/new.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employee_show', methods: ['GET'])]
    public function show(Employee $employee): Response
    {
        dump($employee->getSalaries()->toArray());
        dd($employee->getDeptStories()->toArray());

        return $this->render('employee/show.html.twig', [
            'employee' => $employee,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_employee_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Employee $employee, EmployeeRepository $employeeRepository): Response
    {
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if($form->isValid()) {
                $employeeRepository->save($employee, true);

                $this->addFlash('success','The employee has been successfully updated.');

                return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('error','The employee has not been updated.');
            }
        }

        return $this->renderForm('employee/edit.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employee_delete', methods: ['POST'])]
    public function delete(Request $request, Employee $employee, EmployeeRepository $employeeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$employee->getId(), $request->request->get('_token'))) {
            try {
                $employeeRepository->remove($employee, true);
            } catch(ForeignKeyConstraintViolationException $e) {
                //dump($e);die;
                $this->addFlash('error','This employee has salaries!');

                return $this->redirectToRoute('app_employee_show', ['id' => $employee->getId()], 303);
            }
        }

        return $this->redirectToRoute('app_employee_index', [], Response::HTTP_SEE_OTHER);
    }
}
