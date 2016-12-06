<?php
namespace nvbooster\TestingBundle\SortingConfig;

use nvbooster\SortingManager\SortingManager;
use nvbooster\SortingManager\AbstractConfig;

class CustomConfig extends AbstractConfig
{
    public function __construct(SortingManager $manager)
    {
        $this->manager = $manager;
    }

    public function getName()
    {
        return 'customsort';
    }

    public function getColumns()
    {
        return array(
            'name' => array(
                'name' => 'name',
                'field' => 'a.name',
                'options' => array()
            ),
            'brand' => array(
                'name' => 'brand',
                'field' => 'a.brand',
                'options' => array()
            ),
            'year' => array(
                'name' => 'year',
                'field' => 'a.year',
                'options' => array()
            ),
            'stock' => array(
                'name' => 'stock',
                'field' => 'a.stock',
                'options' => array()
            )
        );
    }

    protected function getSortingDefaults()
    {
        return array(
            'stock' => -1
        );
    }
}