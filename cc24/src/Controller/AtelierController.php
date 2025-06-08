<?php

namespace App\Controller;

use App\Entity\Atelier;
use App\Form\AtelierType;
use App\Repository\AtelierRepository;
use App\Repository\SatisfactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/atelier')]
final class AtelierController extends AbstractController
{
    #[Route(name: 'app_atelier_index', methods: ['GET'])]
    public function index(AtelierRepository $atelierRepository): Response
    {
        $user = $this->getUser();
        if ($user != null && in_array('ROLE_INSTRUCTEUR', $user->getRoles())){
            return $this->render('atelier/index.html.twig', [
                'ateliers_user' => $atelierRepository->findBy(['instructeur' => $user]),
                'ateliers_other' => $atelierRepository->findOthersThanInstructor($user->getId()),
                'user' => $user,
            ]);
        }
        else {
            return $this->render('atelier/index.html.twig', [
                'ateliers_user' => null,
                'ateliers_other' => $atelierRepository->findAll(),
            ]);
        }
    }
    #[Route('/details_ateliers', name: 'app_ateliers_detail', methods: ['GET'])]
    public function detailed(AtelierRepository $atelierRepository): Response
    {
        return $this->index($atelierRepository);
        /*return $this->render('atelier/ateliers.html.twig', [
            'ateliers' => $atelierRepository->findAll(),
        ]);*/
    }

    #[Route('/accueil', name: 'app_atelier_accueil', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('atelier/about.html.twig', []);
    }

    #[Route('/new', name: 'app_atelier_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_INSTRUCTEUR')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $atelier = new Atelier();
        $form = $this->createForm(AtelierType::class, $atelier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $atelier->setInstructeur($this->getUser());
            $entityManager->persist($atelier);
            $entityManager->flush();

            return $this->redirectToRoute('app_atelier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('atelier/new.html.twig', [
            'atelier' => $atelier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_atelier_show', methods: ['GET'])]
    public function show(Atelier $atelier, SatisfactionRepository $satisfactionRepository): Response
    {
        // Vérifier si l'utilisateur est un apprenti inscrit à cet atelier, à utiliser pour la notation
        $isApprentiInscrit = $atelier->getApprentis()->contains($this->getUser());

        $stats = $satisfactionRepository->findSatisfactionStatsByAtelier($atelier);
        $notes = $satisfactionRepository->getNotesForAtelier($atelier);

        return $this->render('atelier/show.html.twig', [
            'atelier' => $atelier,
            'isApprentiInscrit' => $isApprentiInscrit,
            'stats' => [
                'average_note' => $stats['average_note'],
                'notes' => $notes
            ]
        ]);
    }

    #[Route('/{id}/edit', name: 'app_atelier_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Atelier $atelier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AtelierType::class, $atelier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_atelier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('atelier/edit.html.twig', [
            'atelier' => $atelier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_atelier_delete', methods: ['POST'])]
    #[IsGranted('ROLE_INSTRUCTEUR')]
    public function delete(Request $request, Atelier $atelier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$atelier->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($atelier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_atelier_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/confirmationInscription', name: 'app_atelier_confirmationInscription', methods: ['GET','POST'])]
    public function confirmationInscription(Atelier $atelier): Response
    {
        return $this->render('atelier/confirmation_inscription.html.twig', [
            'atelier' => $atelier,
        ]);
    }
    #[Route('/{id}/inscription', name: 'app_atelier_inscription', methods: ['GET','POST'])]
    public function inscription(Atelier $atelier, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); // Récupération de l'utilisateur connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour vous inscrire à un atelier.');
        }

        if (!$user->getAteliersInscrits()->contains($atelier)) {
            $user->addAteliersInscrit($atelier);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_atelier_index');
    }

    #[Route('/{id}/confirmationDesinscription', name: 'app_atelier_confirmationDesinscription', methods: ['GET','POST'])]
    public function confirmationDesinnscription(Atelier $atelier): Response
    {
        return $this->render('atelier/confirmation_desinscription.html.twig', [
            'atelier' => $atelier,
        ]);
    }
    #[Route('/atelier/{id}/desinscription', name: 'app_atelier_desinscription')]
    public function desinscription(Atelier $atelier, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($user->getAteliersInscrits()->contains($atelier)) {
            $user->removeAteliersInscrit($atelier);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_atelier_index');
    }

    #[Route('/{id}/apprentis', name: 'app_atelier_apprentis', methods: ['GET','POST'])]
    public function voirApprentis(Atelier $atelier): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->getUser() !== $atelier->getInstructeur()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette page.');
        }

        return $this->render('atelier/apprentis.html.twig', [
            'atelier' => $atelier,
            'apprentis' => $atelier->getApprentis(),
        ]);
    }

    #[Route('mesAteliers', name: 'app_ateliers_mesAteliers', methods: ['GET','POST'])]
    public function voirMesAteliers(): Response
    {
        $user = $this->getUser();
        return $this->render('atelier/mes_ateliers.html.twig', [
            'ateliers' => $user->getAteliersInscrits(),
        ]);
    }


}
