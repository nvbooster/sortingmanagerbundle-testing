<?php
namespace nvbooster\NvboosterTestingBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SessionStorageTestControllerTest extends WebTestCase
{
    public function testIfActionCalled()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/session');

        self::assertGreaterThan(
            0,
            $crawler->filter('h1:contains("Testing session storage")')->count()
        );
    }

    public function testWorkflow()
    {
        $client = static::createClient();

        $client->request('GET', '/session?sc=state&so=-1');
        $crawler = $client->request('GET', '/session?sc=name&so=1');

        $list = $crawler->filter('.sorting li');

        self::assertCount(2, $list);
        self::assertContains('name - ASC', $list->eq(0)->text());
        self::assertContains('state - DESC', $list->eq(1)->text());
    }
}