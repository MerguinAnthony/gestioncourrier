<?php
// src/Controller/ArchiveMailController.php

namespace App\Controller;

use App\Entity\Archmail;
use App\Form\SearchFormType;
use App\Form\ArchiveMailType;
use App\Repository\ArchmailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArchiveMailController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/gestion-courriers', name: 'app_mail_index')]
    public function index(
        Request $request,
        EntityManagerInterface $manager,
        ArchmailRepository $archmailRepository,
        PaginatorInterface $paginatorInterface,
        Security $security
    ): Response {
        $archmail = new Archmail();
        $form = $this->createForm(ArchiveMailType::class, $archmail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($archmail);
            $manager->flush();
            $this->addFlash('success', 'Courrier ajouté avec succès');

            return $this->redirectToRoute('app_mail_index');
        }

        $searchForm = $this->createForm(SearchFormType::class);
        $searchForm->handleRequest($request);

        $queryBuilder = $archmailRepository->createQueryBuilder('m');

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $query = $searchForm->get('query')->getData();

            if ($query) {
                $queryBuilder
                    ->where('LOWER(m.sender) LIKE LOWER(:query)')
                    ->orWhere('LOWER(m.object) LIKE LOWER(:query)')
                    ->setParameter('query', '%' . $query . '%');
            }
        }

        $nbPagination = $security->getUser()->getPagination();
        $archmailsQuery = $queryBuilder->getQuery();
        $pagination = $paginatorInterface->paginate(
            $archmailsQuery,
            $request->query->getInt('page', 1),
            $nbPagination
        );

        return $this->render('pages/archivemail/index_courrier.html.twig', [
            'archmails' => $pagination,
            'searchForm' => $searchForm->createView(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/suggestions', name: 'app_mail_suggestions')]
    public function suggestions(Request $request, ArchmailRepository $archmailRepository): JsonResponse
    {
        $query = $request->query->get('query', '');
        $suggestions = [];

        if ($query) {
            $suggestions = $archmailRepository->createQueryBuilder('m')
                ->where('LOWER(m.sender) LIKE LOWER(:query)')
                ->orWhere('LOWER(m.object) LIKE LOWER(:query)')
                ->setParameter('query', '%' . $query . '%')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();
        }

        return new JsonResponse($suggestions);
    }

    // Méthode pour Récupérer un fichier stocké dans La variable d'environnement DOWNLOAD_DIRECTORY=/Users/anthonymerguin/Downloads dans le fichier .env de votre projet, 
    // et l'afficher dans une nouvelle fenêtre du navigateur, Pour pouvoir la visualiser.

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/visualiser/{id}', name: 'app_mail_download')]
    public function download(ArchmailRepository $archmailRepository, $id): BinaryFileResponse
    {
        // Récupérer l'entité Archmail correspondante
        $archmail = $archmailRepository->find($id);

        // Vérifier si l'entité Archmail existe
        if (!$archmail) {
            throw $this->createNotFoundException('Le courrier demandé n\'existe pas');
        }

        // Récupérer le nom du fichier depuis l'entité Archmail
        $fileName = $archmail->getFileName1();

        // Construire le chemin complet du fichier à télécharger
        $filePath = $this->getParameter('download_directory') . $fileName;

        // géré les erreurs si le fichier n'existe pas
        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('Le fichier demandé n\'existe pas');
        }
        // Créer une réponse avec le fichier
        $response = new BinaryFileResponse($filePath);

        // Définir le nom du fichier dans l'en-tête de navigation
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $fileName
        );

        // Retourner la réponse
        return $response;
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/modifier/{id}', name: 'app_mail_edit')]
    public function edit(
        Request $request,
        EntityManagerInterface $manager,
        ArchmailRepository $archmailRepository,
        $id
    ): Response {
        // Récupérer l'entité Archmail correspondante
        $archmail = $archmailRepository->find($id);

        // Chemin complet du fichier
        $filePath = $this->getParameter('download_directory');

        // Créer le formulaire avec l'entité Archmail
        $form = $this->createForm(ArchiveMailType::class, $archmail);
        $form->handleRequest($request);

        // Vérifier si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash('success', 'Courrier modifié avec succès');

            return $this->redirectToRoute('app_mail_index');
        }

        // Retourner une réponse avec le formulaire
        return $this->render('pages/archivemail/edit.html.twig', [
            'form' => $form->createView(),
            'filePath' => $filePath,
            'archmail' => $archmail
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/supprimer/{id}', name: 'app_mail_delete')]
    public function delete(
        EntityManagerInterface $manager,
        ArchmailRepository $archmailRepository,
        $id
    ): Response {
        // Récupérer l'entité Archmail correspondante
        $archmail = $archmailRepository->find($id);

        // Vérifier si l'entité Archmail existe
        if (!$archmail) {
            throw $this->createNotFoundException('Le courrier demandé n\'existe pas');
        }

        // Supprimer l'entité Archmail
        $manager->remove($archmail);
        $manager->flush();
        $this->addFlash('success', 'Courrier supprimé avec succès');

        return $this->redirectToRoute('app_mail_index');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/modifier-pagination-5', name: 'app_mail_change_paginate_5')]
    public function changePaginate5(
        Request $request,
        EntityManagerInterface $manager,
        Security $security
    ): Response {
        $user = $security->getUser();
        $user->setPagination(5);
        $manager->flush();
        $this->addFlash('success', 'Pagination modifiée avec succès');

        return $this->redirectToRoute('app_mail_index');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/modifier-pagination-10', name: 'app_mail_change_paginate_10')]
    public function changePaginate10(
        Request $request,
        EntityManagerInterface $manager,
        Security $security
    ): Response {
        $user = $security->getUser();
        $user->setPagination(10);
        $manager->flush();
        $this->addFlash('success', 'Pagination modifiée avec succès');

        return $this->redirectToRoute('app_mail_index');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/modifier-pagination-50', name: 'app_mail_change_paginate_50')]
    public function changePaginate50(
        Request $request,
        EntityManagerInterface $manager,
        Security $security
    ): Response {
        $user = $security->getUser();
        $user->setPagination(50);
        $manager->flush();
        $this->addFlash('success', 'Pagination modifiée avec succès');

        return $this->redirectToRoute('app_mail_index');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/modifier-pagination-100', name: 'app_mail_change_paginate_100')]
    public function changePaginate100(
        Request $request,
        EntityManagerInterface $manager,
        Security $security
    ): Response {
        $user = $security->getUser();
        $user->setPagination(100);
        $manager->flush();
        $this->addFlash('success', 'Pagination modifiée avec succès');

        return $this->redirectToRoute('app_mail_index');
    }
}
