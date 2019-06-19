<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
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
//        Création des User
            $user = new User();
            $user->setUsername('user1');
            $user->setEmail('christophe@perrotin.eu');
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->encoder->encodePassword($user, '123456'));
            $manager->persist($user);
            $manager->flush();

    }
}
