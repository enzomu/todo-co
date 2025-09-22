<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Security\TaskVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_list')]
    public function list(TaskRepository $taskRepository): Response
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            $tasks = $taskRepository->findAll();
        } else {
            $tasks = $taskRepository->findByUser($user);
        }

        return $this->render('task/list.html.twig', [
            'tasks' => $tasks
        ]);
    }

    #[Route('/tasks/done', name: 'task_list_done')]
    public function listDone(TaskRepository $taskRepository): Response
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            $tasks = $taskRepository->createQueryBuilder('t')
                ->where('t.isDone = true')
                ->orderBy('t.createdAt', 'DESC')
                ->getQuery()
                ->getResult();
        } else {
            $tasks = $taskRepository->createQueryBuilder('t')
                ->where('t.author = :user')
                ->andWhere('t.isDone = true')
                ->setParameter('user', $user)
                ->orderBy('t.createdAt', 'DESC')
                ->getQuery()
                ->getResult();
        }

        return $this->render('task/list.html.twig', [
            'tasks' => $tasks,
            'page_title' => 'Tâches terminées'
        ]);
    }

    #[Route('/tasks/create', name: 'task_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setAuthor($this->getUser());

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été ajoutée avec succès.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function edit(Task $task, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(TaskVoter::EDIT, $task);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'La tâche a été modifiée avec succès.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggle(Task $task, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(TaskVoter::TOGGLE, $task);

        $task->toggle(!$task->isDone());
        $em->flush();

        $status = $task->isDone() ? 'terminée' : 'à faire';
        $this->addFlash('success', "La tâche a été marquée comme {$status}.");

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function delete(Task $task, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted(TaskVoter::DELETE, $task);

        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a été supprimée avec succès.');

        return $this->redirectToRoute('task_list');
    }
}