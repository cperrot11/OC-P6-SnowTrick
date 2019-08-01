<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($a=1; $a<10; $a++){
            $user = new User();
            $user->setUsername($faker->unique()->lastName);
            $user->setPassword($this->encoder->encodePassword($user, '123456'));
            $user->setEmail($faker->unique()->companyEmail);
            $user->setActiv(1);
            $manager->persist($user);
            $this->addReference('user_ref'.$a, $user);
        }
        $manager->flush();
    }
}
