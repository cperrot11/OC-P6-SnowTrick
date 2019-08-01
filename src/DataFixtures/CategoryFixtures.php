<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $names = array('grabs','rotations','slides');
        foreach ($names as $cpt=>$name){
            $cat = new Category();
            $cat->setName($name);
            $val = (int)$cpt+1;
            $this->addReference('category_ref'.$val, $cat);
            $manager->persist($cat);
        }
        $manager->flush();
    }
}
