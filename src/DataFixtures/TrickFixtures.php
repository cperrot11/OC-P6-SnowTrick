<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($b=1;$b<20;$b++){
            $trick = new Trick();
            $trick->setName($faker->unique()->word);
            $trick->setCreatedAt($faker->dateTimeBetween('-6months'));
            $content = '<p>' . join($faker->paragraphs(1), '</p><p>') . '</p>';
            $trick->setDescription(($content));
            $this->addReference('trick_ref'.$b, $trick);
            $manager->persist($trick);
        }
        $manager->flush();
    }
}
