<?php
namespace nvbooster\NvboosterTestingBundle\Tests\ConfigStorage;

use PHPUnit\Framework\TestCase;
use nvbooster\SortingManager\SortingManager;
use nvbooster\SortingManager\GenericConfig;
use nvbooster\SortingManagerBundle\ConfigStorage\CookieStorage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

class CookieStorageTest extends TestCase
{
    public function getStorage($cookies = array())
    {
        $request = Request::create('/', 'GET', array(), $cookies);

        $stack = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock();

        $stack
            ->expects($this->once())
            ->method('getMasterRequest')
            ->will($this->returnValue($request));

        return new CookieStorage($stack);
    }

    public function testIfConstructed()
    {
        $storage = $this->getStorage();

        self::assertInstanceOf(CookieStorage::class, $storage);
        self::assertEquals('cookie', $storage->getAlias());
    }

    public function testStorage()
    {
        $storage = $this->getStorage();

        $manager = new SortingManager(array(
            'storage' => 'cookie'
        ));
        $manager->registerStorage($storage);

        $config1 = new GenericConfig($manager);
        $config2 = new GenericConfig($manager);
        $config3 = new GenericConfig($manager);

        $config1
            ->setName('dummy_1')
            ->addColumn('name1', 'n.name1')
            ->addColumn('name2', 'n.name2')
            ->setDefaultSorting(array('name1' => 1));
        $config2
            ->setName('dummy_1')
            ->addColumn('name1', 'n.name1')
            ->addColumn('name2', 'n.name2')
            ->setDefaultSorting(array('name1' => 1));

        $config3->setName('dummy_2');

        self::assertFalse($storage->has($config1));

        $config1->setSortingSequence(array('name2' => -1));
        $storage->store($config1);

        self::assertTrue($storage->has($config2));
        self::assertFalse($storage->has($config3));

        $storage->retrieve($config2);
        self::assertEquals(array('name2' => -1), $config2->getSortingSequence());


        $storageCons = $this->getStorage($storage->getUpdates());

        $managerCons = new SortingManager(array(
            'storage' => 'cookie'
        ));
        $managerCons->registerStorage($storageCons);

        $config4 = new GenericConfig($managerCons);

        $config4
            ->setName('dummy_1')
            ->addColumn('name1', 'n.name1')
            ->addColumn('name2', 'n.name2')
            ->setDefaultSorting(array('name1' => 1));

        $storageCons->retrieve($config4);
        self::assertEquals(array('name2' => -1), $config4->getSortingSequence());
    }
}