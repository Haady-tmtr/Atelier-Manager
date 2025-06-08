<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\UserRepository;

final class UserController extends AbstractController
{
    #[Route('/admin', name: 'adminUsers')]
    #[IsGranted('ROLE_ADMIN')]
    public function manageUsers(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/admin/makeadmin/{id}', name: 'makeAdmin')]
    #[IsGranted('ROLE_ADMIN')]
    public function makeAdmin(User $user, EntityManagerInterface $em): RedirectResponse
    {
        $user->setRoles(['ROLE_ADMIN']);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('adminUsers');
    }

    #[Route('/admin/removeadmin/{id}', name: 'removeAdmin')]
    #[IsGranted('ROLE_ADMIN')]
    public function removeAdmin(User $user, EntityManagerInterface $em): RedirectResponse
    {
        $user->setRoles(['ROLE_INSTRUCTEUR']);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('adminUsers');
    }

    #[Route('/admin/makeinstructeur/{id}', name: 'makeInstructeur')]
    #[IsGranted('ROLE_ADMIN')]
    public function makeInstructor(User $user, EntityManagerInterface $em): RedirectResponse
    {
        $roles = $user->getRoles();
        if (!in_array('ROLE_INSTRUCTEUR', $roles)) {
            $roles[] = 'ROLE_INSTRUCTEUR';
        }
        $user->setRoles($roles);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('adminUsers');
    }

    #[Route('/admin/removeinstructeur/{id}', name: 'removeInstructeur')]
    #[IsGranted('ROLE_ADMIN')]
    public function removeInstructor(User $user, EntityManagerInterface $em): RedirectResponse
    {
        $roles = array_diff($user->getRoles(), ['ROLE_INSTRUCTEUR']);
        $user->setRoles(array_values($roles));
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('adminUsers');
    }
}
