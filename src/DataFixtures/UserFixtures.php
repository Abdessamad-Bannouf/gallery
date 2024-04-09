<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        // on crée 4 images avec noms et dates "aléatoires" en français
        $users = Array();
        for ($i = 0; $i < 10; $i++) {
            $users[$i] = new User();
            $users[$i]->setEmail($faker->email);
            $users[$i]->setRoles(['ROLE_USER']);
            $users[$i]->setPassword($faker->password);

            $manager->persist($users[$i]);

            if($i === 9) {
                $user = new User;

                $user->setEmail('user@test.com')
                    ->setRoles(["ROLE_USER"])
                    ->setPassword($this->encoder->hashPassword($user, 'user'));

                $manager->persist($user);

                $user = new User;

                $user->setEmail('admin@test.com')
                    ->setRoles(["ROLE_ADMIN"])
                    ->setPassword($this->encoder->hashPassword($user, 'admin'));

                $manager->persist($user);
            }
        }

        $manager->flush();
    }
}