<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 5; $i++) {
        $user = new User();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            'password'
        );
        $user->setEmail($faker->email())
            ->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setPhonenumber($faker->phoneNumber())
            ->setPassword($hashedPassword)
            ->setRoles(['ROLE_USER']);

        $this->addReference('user_' . $i, $user);

            $manager->persist($user);
        }
        $manager->flush();
    }
}
