<?php
namespace nvbooster\NvboosterTestingBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TwigExtensionTestControllerTest extends WebTestCase
{
    public function testIfActionCalled()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/twig');

        self::assertGreaterThan(
            0,
            $crawler->filter('h1:contains("Testing twig extension")')->count()
        );
    }

    public function testRepresentaion()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/twig');

        $list = $crawler->filter('.sorting li span');

        self::assertCount(4, $list);

        $column = $list->filter('#cname a');
        self::assertCount(1, $column);
        self::assertContains('Person`s name', $column->text());
        self::assertEquals(array(
                'sm_column',
                'sm_asc'
            ),
            explode(' ', $column->attr('class'))
        );

        $column = $list->filter('#cbd a');
        self::assertCount(1, $column);
        self::assertContains('His birthday', $column->text());
        self::assertEquals(array(
                'sm_column',
                'sm_desc'
            ),
            explode(' ', $column->attr('class'))
        );

        $column = $list->filter('#cstate a');
        self::assertCount(1, $column);
        self::assertContains('His current state', $column->text());
        self::assertEquals(array(
                'sm_column'
            ),
            explode(' ', $column->attr('class'))
        );

        $column = $list->filter('#cextra');
        self::assertCount(1, $column);
        self::assertCount(0, $column->filter('a'));
        self::assertContains('Extra', $column->text());
    }
}