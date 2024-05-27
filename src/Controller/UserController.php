<?php

namespace App\Controller;

use App\Form\NewUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/utilisateur', name: 'app_user_index')]
    public function index(
        UserRepository $userRepository
    ): Response {
        $users = $userRepository->findAll();
        // dd($users);

        return $this->render('pages/user/index_user.html.twig', [
            'users' => $users,
        ]);
    }

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/user/nouveau', name: 'app_user_new')]
    public function new(
        EntityManagerInterface $manager,
        Request $request,
        UserPasswordHasherInterface $passwordEncoder,
        UserRepository $userRepository
    ): Response {
        // Create form
        $form = $this->createForm(NewUserType::class);
        $form->handleRequest($request);

        if (
            $form->isSubmitted() && $form->isValid()
        ) {
            $user = $form->getData();

            // Vérifier l'unicité de l'e-mail
            $existingUser = $userRepository->findOneBy(['email' => $user->getEmail()]);
            if ($existingUser) {
                $this->addFlash('warning', 'L\'e-mail renseigné existe déjà.');

                return $this->redirectToRoute('app_user_index');
            }

            // Encode password
            $user->setPassword($passwordEncoder->hashPassword($user, $user->getPassword()));

            // setPagination
            $user->setPagination(5);

            $roles = $request->request->getInt('roles');
            $user->changeAccountType($roles);


            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Utilisateur créé avec succès');

            return $this->redirectToRoute('app_user_index');
        }


        return $this->render('pages/user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/utilisateur/modifier/{id}', name: 'app_user_edit')]
    public function edit(
        EntityManagerInterface $manager,
        Request $request,
        UserPasswordHasherInterface $passwordEncoder,
        UserRepository $userRepository,
        int $id
    ): Response {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $form = $this->createForm(NewUserType::class, $user);
        $form->handleRequest($request);

        if (
            $form->isSubmitted() && $form->isValid()
        ) {
            $user = $form->getData();

            // Encode password
            $user->setPassword($passwordEncoder->hashPassword($user, $user->getPassword()));

            $roles = $request->request->getInt('roles');
            $user->changeAccountType($roles);

            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Utilisateur modifié avec succès');

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/utilisateur/supprimer/{id}', name: 'app_user_delete')]
    public function delete(
        EntityManagerInterface $manager,
        UserRepository $userRepository,
        Security $security,
        int $id
    ): Response {
        $user = $userRepository->find($id);
        $currentUser = $security->getUser();

        if ($currentUser === $user) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer votre propre compte');
            return $this->redirectToRoute('app_user_index');
        }

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $manager->remove($user);
        $manager->flush();
        $this->addFlash('success', 'Utilisateur supprimé avec succès');

        return $this->redirectToRoute('app_user_index');
    }
}
