<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($c=1; $c<10; $c++) {
            $comment = new Comment();
            $rand_user=rand(1,9);
            $user = $this->getReference('user_ref'.$rand_user);
            $rand_trick=rand(1,19);
            $trick = $this->getReference('trick_ref'.$rand_trick);

            $content = '<p>'. join($faker->paragraphs(1), '</p><p>').'</p>';

            $comment->setCreationDate($faker->dateTimeBetween('-2months'))
                ->setTrick($trick)
                ->setDescription($content)
                ->setUser($user);

            $manager->persist($comment);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
