<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // $faker = Factory::create('fr_FR');
        $picture_list = array('180', '360', 'backflip', 'frontflip', 'japanair');

        foreach ($picture_list as $cpt=>$value){
            $picture = new Picture();
//            $picture->setFile($faker->imageUrl(640, 480, 'sports'));
            $picture->setFile('../images/'.$value.'.jpg');
            $rand_trick=rand(1,19);
            $trick = $this->getReference('trick_ref'.$rand_trick);
            $picture->setTrick($trick);
            $manager->persist($picture);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            TrickFixtures::class,
        );
    }
}
