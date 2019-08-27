<?php
/**
 * Short description
 *
 * PHP version 7.2
 *
 * @category
 * @package
 * @author Christophe PERROTIN
 * @copyright 2018
 * @license MIT License
 * @link http://wwww.perrotin.eu
 */

namespace App\Entity;


use PHPUnit\Framework\TestCase;
use App\Entity\User;

class UserTest extends TestCase
{
    private $user;

    public function setUp():void
    {
        $this->user = new User();
    }

    public function testId()
    {
        $this->assertIsInt($this->user->getId());
    }

    public function testUsername()
    {
        $this->user->setUsername("christophe");
        $this->assertSame('christophe',$this->user->getUsername());
    }
    public function testRoles()
    {
        $this->user->setRoles(['chef']);
        $this->assertIsArray($this->user->getRoles());
    }
    public function testPasswordRequestedAt()
    {
        $this->user->setPasswordRequestedAt(new \DateTime());
        $this->assertInstanceOf(\DateTime::class, $this->user->getPasswordRequestedAt());
    }
    public function testActiv()
    {
        $this->user->setActiv(false);
        $this->assertIsBool($this->user->getActiv());
        $this->assertFalse($this->user->getActiv());
    }

    public function testAddRemoveComment()
    {
        $comment = new Comment();
        $comment->setDescription('Mycomment');
        //add
        $this->user->addComment($comment);
        $this->assertEquals(1,count($this->user->getComments()));
        //delete
        $this->user->removeComment($comment);
        $this->assertEquals(0,count($this->user->getComments()));
    }

    public function tearDown(): void
    {
        $this->user = null;
    }


}
