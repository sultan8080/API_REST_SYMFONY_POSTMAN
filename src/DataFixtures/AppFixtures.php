<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\Author;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture

{
    private $userPasswordHasher;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    
    public function load(ObjectManager $manager): void
    {
         // Création d'un user "normal"
         $user = new User();
         $user->setEmail("user@bookapi.com");
         $user->setRoles(["ROLE_USER"]);
         $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
         $manager->persist($user);
         
         // Création d'un user admin
         $userAdmin = new User();
         $userAdmin->setEmail("admin@bookapi.com");
         $userAdmin->setRoles(["ROLE_ADMIN"]);
         $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "password"));
         $manager->persist($userAdmin);
         
        $faker = Factory::create();

        $listAuthor = [];
        for ($i = 0; $i < 10; $i++) {
            // Création de l'auteur lui-même.
            $author = new Author();
            $author->setFirstName($faker->name(1));
            $author->setLastName($faker->name(1));
            $manager->persist($author);
            // On sauvegarde l'auteur créé dans un tableau.
            $listAuthor[] = $author;
        }


        // Création d'une vingtaine de livres ayant pour titre
        for ($i = 0; $i < 20; $i++) {
            $book = new Book();
            $book->setTitle($faker->sentence(3));
            $book->setCoverText($faker->text());
            // On lie le livre à un auteur pris au hasard dans le tableau des auteurs.
            $book->setAuthor($listAuthor[array_rand($listAuthor)]);
            $manager->persist($book);
        }
        $manager->flush();
    }
}
