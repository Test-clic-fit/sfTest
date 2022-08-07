<?php

namespace App\DataFixtures;

use App\Entity\Craiglist;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class vItemFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {


        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 25; $i++) {
            $item = new Craiglist();
            $item->setTitle($faker->randomElement(['marteau', 'tondeuse', 'tournevis', 'scie', 'vis']))
                ->setDescription($faker->text(200))
                ->setPicture($faker->randomElement(['marteau.jpg', 'scie.jpg', 'tondeuse.jpg', 'tournevis.jpg', 'vis.jpg']))
                ->setDate($faker->dateTime('now'))
                ->setZipcode($faker->randomNumber(5, true));
            $reference = $faker->numberBetween(0, 4);
            $user = $this->getReference('user_' . $reference);
            if ($user instanceof User) {
                $item->setVendor($user);
            }

            $manager->persist($item);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return
            [
                UserFixtures::class
            ];
    }
}