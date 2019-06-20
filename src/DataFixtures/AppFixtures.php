<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var  UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * UserFixtures constructor.
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

//        Création des User
        for ($a=1; $a<10; $a++){
            $user = new User();
            $user->setUsername($faker->lastName);
            $user->setPassword($this->encoder->encodePassword($user, '123456'));
            $user->setEmail($faker->unique()->companyEmail);
            $manager->persist($user);
            $this->addReference('user_ref'.$a, $user);
        }
        $manager->flush();
//        Création des catégories
        $names = array('grabs','rotations','slides');
        foreach ($names as $cpt=>$name){
            $cat = new Category();
            $cat->setName($name);
            $val = (int)$cpt+1;
            $this->addReference('category_ref'.$val, $cat);
            $manager->persist($cat);
        }
        $manager->flush();
//        Création des tricks
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
//        Création des commentaires
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
//        Création des images
        for ($d=1; $d<20; $d++){
            $picture = new Picture();
            $picture->setFile($faker->imageUrl(640, 480, 'sports'));
            $rand_trick=rand(1,19);
            $trick = $this->getReference('trick_ref'.$rand_trick);
            $picture->setTrick($trick);
            $manager->persist($picture);
        }
        $manager->flush();
    }
}
