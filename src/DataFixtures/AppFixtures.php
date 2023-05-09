<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        // Création d'une vingtaine de livres ayant pour titre
        for ($i = 0; $i < 20; $i++) {
            $livre = new Book;
            $livre->setTitle($faker->name());
            $livre->setCoverText($faker->text());
            $manager->persist($livre);
        }
        $manager->flush();
    }
}
