<?php

namespace App\DataFixtures;


use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategoryFixtures extends Fixture
{
    public const CATEGORY_IMAGE_REFERENCE = 'image-category';

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        // on crée 4 catégories avec des noms en français
        $cat = ['Aquatique','Spirituel','Nature','Féérique'];
        $categories = Array();

        for ($i = 0; $i < count($cat); $i++) {
            $categories[$i] = new Category();
            $categories[$i]->setName($cat[$i]);

            $manager->persist($categories[$i]);
            $this->addReference(self::CATEGORY_IMAGE_REFERENCE.'_'.$i, $categories[$i]);

        }

        $manager->flush();
    }
}