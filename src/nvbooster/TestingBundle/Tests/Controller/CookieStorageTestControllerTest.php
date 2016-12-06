<?php
namespace nvbooster\NvboosterTestingBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CookieStorageTestControllerTest extends WebTestCase
{
    public function testIfActionCalled()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/cookie');

        self::assertGreaterThan(
            0,
            $crawler->filter('h1:contains("Testing cookie storage")')->count()
        );
    }

    public function testWorkflow()
    {
        $client = static::createClient();

        $client->request('GET', '/cookie?sc=state&so=-1');
        $crawler = $client->request('GET', '/cookie?sc=name&so=1');

        $list = $crawler->filter('.sorting li');

        self::assertCount(2, $list);
        self::assertContains('name - ASC', $list->eq(0)->text());
        self::assertContains('state - DESC', $list->eq(1)->text());
    }
}