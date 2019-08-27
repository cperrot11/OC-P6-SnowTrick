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

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickControllerTest extends WebTestCase
{
    public function testIndex()
    {
        // asserts a environment LIMIT > 0
        $end = getenv('LIMIT');
        $this->assertGreaterThan(0,$end);
    }
    public function testShow()
    {
        // asserts a specific 200 status code
        $client = static::createClient();
        $client->request('GET', 'trick/blog');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    public function testNoRoute()
    {
        // asserts that the response status code is 404
        $client = static::createClient();
        $client->request('GET', 'administration');

        $this->assertTrue($client->getResponse()->isNotFound());
    }
    /**
     * @dataProvider provideUrls
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function provideUrls()
    {
        return [
            ['/trick/blog'],
            ['/trick/new'],
            // ...
        ];
    }
}
