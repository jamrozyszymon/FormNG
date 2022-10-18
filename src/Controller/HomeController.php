<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Employee;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EmployeeTaskType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/home/{id}', name: 'app_home')]
    public function dynamicForm(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $employee = $entityManager->getRepository(Employee::class)->find($id);

        if (!$employee) {
            throw $this->createNotFoundException(
                'No found employee with id: '.$id
            );
        }

        $originalTask = new ArrayCollection();

        foreach($employee->getTasks() as $task) {
            $originalTask->add($task);
        }


        $form = $this->createForm(EmployeeTaskType::class, $employee);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($originalTask as $task) {
                if(false===$employee->getTasks()->contains($task)) {
                    $task->setEmployee(null);
                    $entityManager->persist($task);
                }
            }
            $entityManager->persist($employee);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_home', ['id' => $id]);
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);

    }
}

