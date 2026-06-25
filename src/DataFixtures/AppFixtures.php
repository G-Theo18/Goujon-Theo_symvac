<?php

namespace App\DataFixtures;

use App\Entity\Vacataire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create(locale: 'fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 50; $i++)
        {
            $vacataire = new Vacataire();
            $vacataire->setNom($this->faker->name());
            $manager->persist($vacataire);
        }

        $manager->flush();
    }
}