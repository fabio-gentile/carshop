<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Advert;
use App\Entity\AdvertImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdvertFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create('fr_FR');
        $users = [];
        // generate 15 users
        for ($i = 0; $i < 15; $i++) {
            $user = new User();
            $hash = $this->passwordHasher->hashPassword($user, 'password');
            $firstName = $faker->firstName();
            $lastName = $faker->lastName();
            $user->setEmail($faker->email())
                ->setPassword($hash)
                ->setPicture('https://ui-avatars.com/api/?name=' . $firstName . '+' . $lastName)
                ->setDescription($faker->paragraphs(2, true))
                ->setFirstName($firstName)
                ->setLastName($lastName);
            $manager->persist($user);
            $users[] = $user;
        }

        // generate 40 cars
        for ($i = 0; $i < 40; $i++) {
            $advert = new Advert();
            $engineDisplacement = [1, 1.2, 1.4, 1.6, 1.8, 2];
            $brands = ['Toyota', 'Ford' , 'Honda', 'Volkswagen', 'BMW'];
            $user = $users[rand(0, count($users) - 1)];
            $fuelType = ['Essence', 'Diesel', 'Électrique', 'Hybride', 'GPL'];
            $transmissions = ['Manuelle', 'Automatique', 'Semi-automatique'];
            $carOptions = ['Climatisation', 'Toit ouvrant', 'Système de navigation', 'Sièges en cuir', 'Régulateur de vitesse'];

            $advert->setBrand($brands[rand(0, count($brands) - 1)])
                ->setModel('Modèle ' . $i)
                ->setTitle($faker->sentence())
                ->setPrice(rand(4000, 25000))
                ->setKilometers(rand(0, 250000))
                ->setCoverImage('https://picsum.photos/600/300')
                ->setTotalOwners(rand(1, 4))
                ->setEngineDisplacement($engineDisplacement[rand(0, count($engineDisplacement) - 1)])
                ->setPower(rand(100, 300))
                ->setFuelType($fuelType[rand(0, count($fuelType) - 1)])
                ->setYearOfRegistration($faker->dateTimeBetween('-20 years'))
                ->setTransmission($faker->randomElement($transmissions))
                ->setSeller($user)
                ->setDescription($faker->paragraphs(4, true))
                ->setCarOptions(join(', ', $faker->randomElements($carOptions, null)));

            for ($j = 0; $j < rand(3,6); $j++) {
                $image = new AdvertImage();
                $image->setUrl('https://picsum.photos/500/250')
                    ->setAdvert($advert)
                    ->setCaption($faker->sentence());

                $manager->persist($image);
            }
            $manager->persist($advert);
        }
        $manager->flush();
    }
}
