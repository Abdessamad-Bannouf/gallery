<?php

namespace App\DataFixtures;


use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ImageFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        // on crée 4 images avec noms et dates "aléatoires" en français
        $images = Array();
        for ($i = 0; $i < 10; $i++) {
            $images[$i] = new Image();
            $images[$i]->setName($faker->imageUrl($width = 640, $height = 480));
            $images[$i]->setDate($faker->dateTimeBetween('2021-01-01', 'now'));
            $images[$i]->addCategory($this->getReference(CategoryFixtures::CATEGORY_IMAGE_REFERENCE.'_'.rand(0,3)));


            $manager->persist($images[$i]);
        }

    $manager->flush();
    }
}