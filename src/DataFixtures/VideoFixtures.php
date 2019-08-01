<?php

namespace App\DataFixtures;

use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class VideoFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $video = new Video();
        $video->setLink('<iframe width="1280" height="720" src="https://www.youtube.com/embed/6QsLhWzXGu0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $rand_trick=rand(1,19);
        $trick = $this->getReference('trick_ref'.$rand_trick);
        $video->setTrick($trick);
        $manager->persist($video);
        $manager->flush();
    }

}
