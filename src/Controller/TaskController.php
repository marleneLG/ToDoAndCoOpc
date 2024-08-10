<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_list', methods: ['GET'])]
    public function listAction(TaskRepository $repo)
    {
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            return $this->render('task/list.html.twig', ['tasks' => $repo->findAllTaskByUserAdmin($this->getUser())]);
        } else {
            return $this->render('task/list.html.twig', ['tasks' => $repo->findAllTaskByUser($this->getUser())]);
        }
    }

    #[Route('/tasks/create', name: 'task_create', methods: ['GET', 'POST'])]
    #[IsGranted(new Expression(
        '"ROLE_ADMIN" in role_names or (is_authenticated())'
    ))]
    public function createAction(Request $request, EntityManagerInterface $em)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit', methods: ['GET', 'POST'])]
    #[IsGranted(new Expression(
        '"ROLE_ADMIN" in role_names or (is_authenticated())'
    ))]
    public function editAction(Task $task, Request $request, EntityManagerInterface $em)
    {
        if ($task->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'Not authorized to update this task');
            return $this->redirectToRoute('task_list');
        }
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle', methods: ['GET', 'POST'])]
    #[IsGranted(new Expression(
        '"ROLE_ADMIN" in role_names or (is_authenticated())'
    ))]
    public function toggleTaskAction(Task $task, EntityManagerInterface $em)
    {
        $task->toggle(!$task->isDone());
        $em->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete', methods: ['GET'])]
    #[IsGranted(new Expression(
        '"ROLE_ADMIN" in role_names or (is_authenticated())'
    ))]
    public function deleteTaskAction(Task $task, EntityManagerInterface $em)
    {
        if ($task->getUser() !== $this->getUser()) {
            if ($task->getUser() === null) {
                if (!$this->isGranted('ROLE_ADMIN')) {
                    $this->addFlash('error', 'Not authorized to delete this task');
                    return $this->redirectToRoute('task_list');
                }
            }

            if (!$this->isGranted('ROLE_ADMIN')) {
                $this->addFlash('error', 'Not authorized to delete this task');
                return $this->redirectToRoute('task_list');
            }
        }
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
