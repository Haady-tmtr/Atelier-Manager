<?php

namespace App\DataFixtures;

use App\Entity\Atelier;
use App\Entity\Satisfaction;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SatisfactionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();  // Création d'une instance Faker

        // Récupérer les ateliers et les apprentis existants
        $ateliers = $manager->getRepository(Atelier::class)->findAll();
        //$apprentis = $manager->getRepository(User::class)->findBy(['roles' => ['ROLE_APPRENTI']]);

        $apprenti1 = $this->getReference("apprenti_1",User::class);
        $apprenti2 = $this->getReference("apprenti_2",User::class);
        $apprenti3 = $this->getReference("apprenti_3",User::class);
        $apprentis = [$apprenti1, $apprenti2, $apprenti3];

        foreach ($ateliers as $atelier) { // Pour chaque atelier,nous allons générer des notations de satisfaction par des apprentis
            foreach ($apprentis as $apprenti) { // Créer une nouvelle note (comprise entre 0 et 5)
                $satisfaction = new Satisfaction();
                $satisfaction->setAtelier($atelier);
                $satisfaction->setApprenti($apprenti);
                $satisfaction->setNote($faker->numberBetween(0, 5));

                // Persist the satisfaction record
                $manager->persist($satisfaction);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,     // Pour que les utilisateurs (apprentis) soient chargés d'abord
            AtelierFixtures::class,  // Pour que les ateliers soient chargés d'abord
        ];
    }
}
