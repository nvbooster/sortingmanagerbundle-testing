<?php
namespace nvbooster\NvboosterTestingBundle\Tests\EventListener;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Cookie;

class SaveSortingCookieListenerTest extends WebTestCase
{
    public function testIfUnrequiredCookiesSet()
    {
        $client = static::createClient();

        $client->request('GET', '/cookie');

        $cookies = $client->getResponse()->headers->getCookies('array');
        self::assertArrayNotHasKey('sort_cookiesort', $cookies);
    }

    public function testIfCookiesSet()
    {
        $client = static::createClient();

        $client->request('GET', '/cookie?sc=state&so=-1');

        $search = null;
        /**
         * @var Cookie $cookie
         */
        foreach ($client->getResponse()->headers->getCookies() as $cookie) {
            if ($cookie->getName() == 'sort_cookiesort') {
                $search = $cookie;
                break;
            }
        }

        self::assertNotNull($search);
    }
}