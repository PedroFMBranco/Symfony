<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixture extends Fixture
{
    private $faker;

    public function __construct() {

        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager) {

        for ($i = 0; $i < 10; $i++) {
            $manager->persist($this->getUser());
        }
        $manager->flush();
    }

    private function getUser(): User {

        return (new User())->create( $this->faker->firstName(),
            $this->faker->lastName(),
            $this->faker->phoneNumber(),
            $this->faker->email(),
            $this->faker->address());
    }
}
