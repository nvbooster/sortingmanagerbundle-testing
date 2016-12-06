<?php
namespace nvbooster\NvboosterTestingBundle\Tests\ConfigStorage;

use PHPUnit\Framework\TestCase;
use nvbooster\SortingManager\SortingManager;
use nvbooster\SortingManager\GenericConfig;
use nvbooster\SortingManagerBundle\ConfigStorage\SessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class SessionStorageTest extends TestCase
{
    public function testIfConstructed()
    {
        $storage = new SessionStorage(new Session(new MockArraySessionStorage()));

        self::assertInstanceOf(SessionStorage::class, $storage);
        self::assertEquals('session', $storage->getAlias());
    }

    public function testStorage()
    {
        $storage = new SessionStorage(new Session(new MockArraySessionStorage()));

        $manager = new SortingManager(array(
            'storage' => 'session'
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
    }
}