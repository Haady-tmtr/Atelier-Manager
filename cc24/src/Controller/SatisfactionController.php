<?php

namespace App\Controller;

use App\Entity\Atelier;
use App\Entity\Satisfaction;
use App\Form\SatisfactionType;
use App\Repository\SatisfactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;


final class SatisfactionController extends AbstractController
{
    /*#[Route('/satisfaction', name: 'app_satisfaction')]
    public function index(): Response
    {
        return $this->render('satisfaction/index.html.twig', [
            'controller_name' => 'SatisfactionController',
        ]);
    }*/

    #[Route('/atelier/{id}/noter', name: 'app_satisfaction_noter')]
    public function noter(Request $request, EntityManagerInterface $entityManager, Atelier $atelier): Response
    {
        $satisfaction = new Satisfaction();
        $satisfaction->setApprenti($this->getUser());
        $satisfaction->setAtelier($atelier);

        $form = $this->createForm(SatisfactionType::class, $satisfaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($satisfaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_atelier_show', ['id' => $atelier->getId()]);
        }

        return $this->render('satisfaction/noter.html.twig', [
            'form' => $form->createView(),
            'atelier' => $atelier
        ]);
    }

    #[Route('/atelier/{id}/satisfaction', name: 'app_satisfaction_stats_atelier')]
    public function stats(Atelier $atelier, SatisfactionRepository $satisfactionRepository): Response
    {
        // Récupérer la moyenne des notes de satisfaction pour l'atelier
        $stats = $satisfactionRepository->findSatisfactionStatsByAtelier($atelier);
        $notes = $satisfactionRepository->getNotesForAtelier($atelier);


        // On vérifie ici que la moyenne existe dans les résultats
        if (empty($stats) || !isset($stats['average_note'])) {
            return $this->render('satisfaction/stats.html.twig', [
                'atelier' => $atelier,
                'error_message' => 'Aucune donnée de satisfaction disponible pour cet atelier.'
            ]);
        }

        return $this->render('satisfaction/stats.html.twig', [
            'atelier' => $atelier,
            'stats' => [
                'average_note' => $stats['average_note'],
                'notes' => $notes
            ]
        ]);
    }
}
