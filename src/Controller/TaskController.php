<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class TaskController extends AbstractController
{

    /**
     * The above PHP code defines two Symfony controller actions for listing tasks and creating a new
     * task, including form handling and database interaction.
     * 
     * @param EntityManagerInterface em The `em` parameter in the Symfony controller actions refers to
     * the `EntityManagerInterface` service, which is responsible for managing your entities and their
     * relationships in the database. It allows you to perform operations like persisting, updating,
     * and deleting entities.
     * 
     * @return In the provided code snippet, the `createAction` method is returning a response based on
     * the form submission and validation process. If the form is submitted and valid, a new task is
     * created, associated with the current user, and persisted to the database. A success flash
     * message is added, and the user is redirected to the `task_list` route.
     */

    #[Route('/task', name: 'task_list')]
    public function listAction(EntityManagerInterface $em)
    {
        return $this->render('task/list.html.twig', ['tasks' => $em->getRepository(Task::class)->findAll()]);
    }

    /**
     * This PHP function creates a new task entity, associates it with the current user, and saves it
     * to the database if the form submission is valid.
     * 
     * @param Request request The `` parameter in the `createAction` method is an instance of
     * the `Request` class in Symfony. It represents an HTTP request and contains information about the
     * request such as the request method, headers, parameters, and more.
     * @param EntityManagerInterface em The "em" parameter in the code snippet stands for
     * EntityManagerInterface. It is used for managing entities in Doctrine, which is an
     * object-relational mapping (ORM) tool for PHP. The EntityManagerInterface allows you to interact
     * with the database by performing operations such as persisting entities, flushing changes to the
     * 
     * @return If the form is submitted and valid, the function will redirect to the 'task_list' route
     * after adding the task to the database and displaying a success flash message. If the form is not
     * submitted or not valid, the function will render the 'task/create.html.twig' template with the
     * form.
     */

    #[Route('/tasks/create', name: 'task_create')]
    public function createAction(Request $request, EntityManagerInterface $em)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $user = $this->getUser()->getId();
        $idUs = $em->getRepository(User::class)->findOneBy(['id' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task->setUser($idUs)
                ->setCreatedAt(new \DateTime());
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien ajoutée.');

            return $this->redirectToRoute('task_list');
        }
        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * This PHP function handles the editing of a task entity, displaying a form to update the task and
     * saving the changes if the form is submitted and valid.
     * 
     * @param Task task The `` parameter in the `editAction` method represents the task entity
     * that is being edited. It is passed as an argument to the controller action when a user accesses
     * the edit task route with a specific task ID.
     * @param Request request The `` parameter in the `editAction` method represents the HTTP
     * request that is being made to the server. It contains information such as the request method
     * (GET, POST, etc.), request headers, request parameters, and more. In this context, the
     * `` parameter is used to
     * @param EntityManagerInterface em The "em" parameter in the code snippet stands for
     * EntityManagerInterface. It is used for managing entities in Doctrine ORM (Object-Relational
     * Mapping) within a Symfony application. The EntityManagerInterface allows you to perform
     * operations such as persisting, updating, and deleting entities in the database. In the context
     * 
     * @return If the form is submitted and valid, the function will return a redirect response to the
     * 'task_list' route. Otherwise, it will return a rendered template for the 'task/edit.html.twig'
     * with the form and task variables passed to it.
     */

    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function editAction(Task $task, Request $request, EntityManagerInterface $em)
    {
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

    /**
     * The function `toggleTaskAction` toggles the completion status of a task and displays a success
     * message before redirecting to the task list page.
     * 
     * @param Task task The `` parameter in the `toggleTaskAction` method represents an instance
     * of the `Task` entity. This entity likely contains information about a specific task in your
     * application, such as its title, status (done or not done), and any other relevant details.
     * @param EntityManagerInterface em The "em" parameter in the code snippet stands for
     * EntityManagerInterface. In Symfony, EntityManagerInterface is an interface that defines the
     * methods for managing entities in the Doctrine ORM (Object-Relational Mapping) system. It
     * provides methods for persisting, removing, and flushing entities to the database.
     * 
     * @return The code snippet is returning a redirection to the route named 'task_list' after
     * toggling the status of a task.
     */

    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTaskAction(Task $task, EntityManagerInterface $em)
    {
        $task->toggle(!$task->isDone());
        $em->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * This PHP function deletes a task if the current user is the owner of the task or has a specific
     * role, otherwise it displays an error message.
     * 
     * @param Task task The `deleteTaskAction` method takes two parameters: `` of type `Task` and
     * `` of type `EntityManagerInterface`.
     * @param EntityManagerInterface em The "em" parameter in the deleteTaskAction method is an
     * instance of EntityManagerInterface. It is used to interact with the database, specifically for
     * removing the task entity from the database using the remove() method and then persisting the
     * changes using the flush() method.
     * 
     * @return If the condition ` ===  || isset([0])` is true, the task will
     * be deleted from the database, a success flash message 'La tâche a bien été supprimée.' will be
     * added, and the user will be redirected to the 'task_list' route.
     */
    
    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function deleteTaskAction(Task $task, EntityManagerInterface $em)
    {
        $idUserTask = $task->getUser()->getId();
        $idUser = $this->getUser()->getId();
        $userRole = $this->getUser()->getRoles();

        if ($idUserTask === $idUser || isset($userRole[0])) {
            $em->remove($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
        } else {
            $this->addFlash('error', "ce n'ai pas votre tache.");
        }

        return $this->redirectToRoute('task_list');
    }
}
