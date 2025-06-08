<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setNom('admin')
            ->setPrenom('Admin')
            ->setEmail('admin@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword($user, 'secret'))
            ->setRoles(['ROLE_ADMIN', 'ROLE_INSTRUCTEUR']);
        $manager->persist($user);

        $user = new User();
        $user->setNom('toto')
            ->setPrenom('Toto')
            ->setEmail('toto@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword($user, 'secret'))
            ->setRoles(['ROLE_INSTRUCTEUR']);
        $manager->persist($user);

        // Apprentis par défaut
        $apprenti1 = new User();
        $apprenti1->setNom("Durand")
            ->setPrenom("Paul")
            ->setEmail("paul.durand@example.com")
            ->setPassword($this->passwordHasher->hashPassword($apprenti1, 'secret'))
            ->setRoles(['ROLE_APPRENTI', 'ROLE_INSTRUCTEUR']);
        $manager->persist($apprenti1);
        $this->addReference("apprenti_1", $apprenti1);


        $apprenti2 = new User();
        $apprenti2->setNom("Lefevre")
            ->setPrenom("Julie")
            ->setEmail("julie.lefevre@example.com")
            ->setPassword($this->passwordHasher->hashPassword($apprenti2, 'secret'))
            ->setRoles(['ROLE_APPRENTI']);
        $manager->persist($apprenti2);
        $this->addReference("apprenti_2", $apprenti2);


        $apprenti3 = new User();
        $apprenti3->setNom("Morel")
            ->setPrenom("Lucas")
            ->setEmail("lucas.morel@example.com")
            ->setPassword($this->passwordHasher->hashPassword($apprenti3, 'secret'))
            ->setRoles(['ROLE_APPRENTI']);
        $manager->persist($apprenti3);
        $this->addReference("apprenti_3", $apprenti3);

        $apprenti4 = new User();
        $apprenti4->setNom("Martin")
            ->setPrenom("Emma")
            ->setEmail("emma.martin@example.com")
            ->setPassword($this->passwordHasher->hashPassword($apprenti4, 'secret'))
            ->setRoles(['ROLE_APPRENTI']);
        $manager->persist($apprenti4);
        $this->addReference("apprenti_4", $apprenti4);

        $apprenti5 = new User();
        $apprenti5->setNom("Bernard")
            ->setPrenom("Hugo")
            ->setEmail("hugo.bernard@example.com")
            ->setPassword($this->passwordHasher->hashPassword($apprenti5, 'secret'))
            ->setRoles(['ROLE_APPRENTI']);
        $manager->persist($apprenti5);
        $this->addReference("apprenti_5", $apprenti5);

        $apprenti6 = new User();
        $apprenti6->setNom("Dubois")
            ->setPrenom("Chloe")
            ->setEmail("chloe.dubois@example.com")
            ->setPassword($this->passwordHasher->hashPassword($apprenti6, 'secret'))
            ->setRoles(['ROLE_APPRENTI']);
        $manager->persist($apprenti6);
        $this->addReference("apprenti_6", $apprenti6);

        $apprenti7 = new User();
        $apprenti7->setNom("Simon")
            ->setPrenom("Nathan")
            ->setEmail("nathan.simon@example.com")
            ->setPassword($this->passwordHasher->hashPassword($apprenti7, 'secret'))
            ->setRoles(['ROLE_APPRENTI']);
        $manager->persist($apprenti7);
        $this->addReference("apprenti_7", $apprenti7);


        $manager->flush();
    }
}
