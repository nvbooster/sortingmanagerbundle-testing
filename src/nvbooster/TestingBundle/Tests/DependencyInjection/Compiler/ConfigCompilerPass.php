<?php
namespace nvbooster\NvboosterTestingBundle\Tests\DependencyInjection\Compiler;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use nvbooster\SortingManager\SortingManager;
use nvbooster\SortingManager\GenericConfig;
use nvbooster\TestingBundle\SortingConfig\CustomConfig;

class ConfigCompilerTest extends KernelTestCase
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

    public function testConfigCompilerPass()
    {
        $container = static::$kernel->getContainer();

        $genericConfig = $container->get('service_sorting_config');
        self::assertInstanceOf(GenericConfig::class, $genericConfig);
        self::assertSame($genericConfig, $this->manager->getConfig('servicesort'));

        $customConfig = $container->get('custom_sorting_config');
        self::assertInstanceOf(CustomConfig::class, $customConfig);
        self::assertSame($customConfig, $this->manager->getConfig('customsort'));
    }
}