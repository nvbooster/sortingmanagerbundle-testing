<?php
namespace nvbooster\NvboosterTestingBundle;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use nvbooster\SortingManager\SortingManager;
use nvbooster\SortingManagerBundle\ConfigStorage\SessionStorage;
use nvbooster\SortingManagerBundle\ConfigStorage\CookieStorage;
use nvbooster\SortingManagerBundle\DependencyInjection\NvboosterSortingManagerExtension;
use nvbooster\SortingManagerBundle\EventListener\SaveSortingCookieListener;
use Symfony\Component\DependencyInjection\Reference;
use nvbooster\TestingBundle\DependencyInjection\NvboosterTestingExtension;

class NvboosterSortingManagerExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions()
    {
        return array(
            new NvboosterSortingManagerExtension()
        );
    }

    public function testIfServiceCreated()
    {
        $this->load(array('enabled' => true, 'storages' => array(
            'cookie' => array('expire' => 7777)
        )));

        self::assertContainerBuilderHasService('nvbooster_sortingmanager', SortingManager::class);

        self::assertContainerBuilderHasService(
            'nvbooster_sortingmanager_storage.cookie',
            CookieStorage::class
        );
        self::assertContainerBuilderHasService(
            'nvbooster_sortingmanager.cookie_save_listener',
            SaveSortingCookieListener::class
        );
        self::assertContainerBuilderHasService(
            'nvbooster_sortingmanager_storage.session',
            SessionStorage::class
        );

        self::assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'nvbooster_sortingmanager',
            'registerStorage',
            array(
                new Reference('nvbooster_sortingmanager_storage.cookie')
            )
        );

        self::assertContainerBuilderHasServiceDefinitionWithArgument(
            'nvbooster_sortingmanager.cookie_save_listener',
            1,
            7777
        );

        self::assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'nvbooster_sortingmanager',
            'registerStorage',
            array(
                new Reference('nvbooster_sortingmanager_storage.session')
            )
        );
    }

    public function testIfServiceCanBeDisabled()
    {
        $this->load(array('enabled' => false));

        self::assertContainerBuilderNotHasService('nvbooster_sortingmanager');
    }

    public function testIfStorageDriversCanBeDisabled()
    {
        $this->load(array('enabled' => true, 'storages' => array(
            'session' => false,
            'cookie' => false
        )));

        self::assertContainerBuilderNotHasService('nvbooster_sortingmanager_storage.cookie');
        self::assertContainerBuilderNotHasService('nvbooster_sortingmanager.cookie_save_listener');
        self::assertContainerBuilderNotHasService('nvbooster_sortingmanager_storage.session');
    }

    public function testIfDefaultSettingsSet()
    {
        $defaults = array(
            'storage' => 'session',
            'sort_columns_count' => 4,
            'param_column' => 'sct',
            'param_order' => 'sot',
            'column_ascend_class' => 'smt_asc',
            'column_descend_class' => 'smt_desc',
            'column_sortable_class' => 'smt_column',
            'translation_domain' => 'sortingmanagertest'
        );

        $this->load(array('enabled' => true, 'defaults' => $defaults));

        self::assertContainerBuilderHasServiceDefinitionWithArgument('nvbooster_sortingmanager', 0, $defaults);
    }
}