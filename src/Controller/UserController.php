<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{

    /**
     * The function `listAction` retrieves all users from the database and renders them in a Twig
     * template for display.
     * 
     * @param EntityManagerInterface em The parameter "em" in the code snippet stands for
     * EntityManagerInterface. It is used for managing entities in the Doctrine ORM (Object-Relational
     * Mapping) system within Symfony. In this context, it is being injected into the listAction method
     * to interact with the database and retrieve all User entities using the
     * 
     * @return The `listAction` method is returning a rendered template `user/list.html.twig` with an
     * array of all users retrieved from the database using the `findAll()` method of the `User` entity
     * repository.
     */

    #[Route('/user', name: 'user_list')]
    #[IsGranted("ROLE_ADMIN")]
    public function listAction(EntityManagerInterface $em)
    {
        return $this->render('user/list.html.twig', ['users' => $em->getRepository(User::class)->findAll()]);
    }

    /**
     * This PHP function creates a new user with admin role, hashes the password, and stores the user
     * in the database upon form submission.
     * 
     * @param Request The `request` parameter in the `createAction` method is an instance of
     * the `Request` class in Symfony. It represents an HTTP request and contains information about the
     * request such as the request method, headers, parameters, and more.
     * @param EntityManagerInterface em EntityManagerInterface is an interface in Doctrine that
     * provides a set of methods for managing entities and their relationships. It is used for
     * persisting, merging, removing, and querying entities in the database. In the provided code
     * snippet,  is an instance of EntityManagerInterface injected into the controller action to
     * interact with
     * @param UserPasswordHasherInterface userPasswordHasher The `` parameter in the
     * `createAction` method is an instance of `UserPasswordHasherInterface`. It is used to securely
     * hash the user's password before storing it in the database. This interface provides methods for
     * hashing and checking passwords, helping to ensure the security of user
     * 
     * @return If the form is submitted and valid, the user will be added to the database, a success
     * flash message will be displayed, and the user will be redirected to the 'user_list' route. If
     * the form is not submitted or not valid, the create form view will be rendered with the form.
     */

    #[Route('/users/create', name: 'user_create')]
    #[IsGranted("ROLE_ADMIN")]
    public function createAction(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * This PHP function edits a user's information, including updating the password securely using a
     * user password hasher, and redirects to the user list page upon successful submission.
     * 
     * @param User user The `` parameter in the `editAction` method represents the user entity
     * that you want to edit. It is being passed as an argument to the method and is used to populate
     * the form with the user's current data and to update the user entity with the new data entered in
     * the form before
     * @param Request request The `` parameter in the `editAction` method is an instance of the
     * Symfony\Component\HttpFoundation\Request class. It represents an HTTP request and contains
     * information such as the request method, headers, parameters, and more. In this context, it is
     * used to handle form submissions and retrieve data from the
     * @param EntityManagerInterface em EntityManagerInterface is an interface in Doctrine that
     * provides a set of methods for managing entities and their relationships. It is used to interact
     * with the database, perform operations like persisting, updating, and deleting entities, and
     * managing transactions. In the given code snippet,  is an instance of EntityManagerInterface
     * injected
     * @param UserPasswordHasherInterface userPasswordHasher The `` parameter in the
     * `editAction` method is an instance of `UserPasswordHasherInterface`. It is used to securely hash
     * the user's password before storing it in the database. This helps to ensure that user passwords
     * are stored in a secure and encrypted format.
     * 
     * @return If the form is submitted and valid, the function will set the hashed password for the
     * user, flush the changes to the database, add a success flash message, and then redirect to the
     * 'user_list' route. If the form is not submitted or not valid, it will render the
     * 'user/edit.html.twig' template with the form and user data.
     */

    #[Route('/users/{id}/edit', name: 'user_edit')]
    #[IsGranted("ROLE_ADMIN")]
    public function editAction(User $user, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
