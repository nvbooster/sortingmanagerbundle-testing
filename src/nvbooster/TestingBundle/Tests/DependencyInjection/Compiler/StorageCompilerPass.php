<?php
namespace nvbooster\NvboosterTestingBundle\Tests\DependencyInjection\Compiler;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use nvbooster\SortingManager\SortingManager;
use nvbooster\SortingManager\ConfigStorage\ArrayStorage;

class StorageCompilerTest extends KernelTestCase
{
    /**
     * @var SortingManager
     */
    protected $manager;

    public function setUp()
    {
        self::bootKernel();

        $this->manager = static::$kernel->getContainer()
            ->get('nvbooster_sortingmanager');
    }

    public function testStorageCompilerPass()
    {
        self::assertInstanceOf(ArrayStorage::class, $this->manager->getStorage('second_array'));
    }
}