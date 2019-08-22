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

namespace App\Controller;

use PHPUnit\Framework\TestCase;

class TrickControllerTest extends TestCase
{
    public function testIndex()
    {
        $end = getenv('LIMIT');
        $this->assertGreaterThan(0,$end);
    }
}
