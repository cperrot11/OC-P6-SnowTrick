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

class UserTest extends TestCase
{

    public function testSetUsername()
    {
        $user = new User();
        $user->setUsername("christophe");
        $this->assertSame('christophe',$user->getUsername());
    }

    public function testAddComment()
    {
        // Comment test ça ?
        $user = new User();
        $comment = new Comment();

    }
}
