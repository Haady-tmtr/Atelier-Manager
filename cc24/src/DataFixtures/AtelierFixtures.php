<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Atelier;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AtelierFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");

        $userRepository = $manager->getRepository(User::class);
        $users = $manager->getRepository(User::class)->findAll();


        if (count($users) === 0) {
            throw new \Exception("Aucun instructeur trouvé en base. Exécutez UserFixture d'abord.");
        }

        $instructeurs = [
            $manager->getRepository(User::class)->findOneBy(['email' => 'admin@gmail.com']),
            $manager->getRepository(User::class)->findOneBy(['email' => 'toto@gmail.com']),
            $this->getReference("apprenti_1",User::class),
        ];

        for ($i = 1; $i <= 15; $i++) {
            $atelier = new Atelier();
            $atelier->setNom("Atelier n°" . $i)
                ->setDescription($faker->paragraph())
                ->setInstructeur($faker->randomElement($instructeurs));


            $apprenti2 = $this->getReference("apprenti_2",User::class);
            $apprenti3 = $this->getReference("apprenti_3",User::class);
            $apprenti4 = $this->getReference("apprenti_4",User::class);
            $apprenti5 = $this->getReference("apprenti_5",User::class);
            $apprenti6 = $this->getReference("apprenti_6",User::class);
            $apprenti7 = $this->getReference("apprenti_7",User::class);

            // Ajouter les apprentis à l'atelier

            $atelier->addApprenti($apprenti2);
            $atelier->addApprenti($apprenti3);
            $atelier->addApprenti($apprenti5);
            $atelier->addApprenti($apprenti7);

            $manager->persist($atelier);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }
}


