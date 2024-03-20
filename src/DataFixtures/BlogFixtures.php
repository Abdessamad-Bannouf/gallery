<?php

namespace App\DataFixtures;


use App\Entity\Blog;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class BlogFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        // on crée 4 images avec noms et dates "aléatoires" en français
        $images = Array();
        for ($i = 0; $i < 20; $i++) {
            $images[$i] = new Blog();
            $images[$i]->setTitre($faker->title);
            $images[$i]->setDescription($faker->text);
            $images[$i]->setDate($faker->dateTimeBetween('2021-01-01', 'now'));

            $manager->persist($images[$i]);
        }

        $manager->flush();
    }
}