<?php

namespace nvbooster\TestingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use nvbooster\SortingManager\SortingManager;
use Symfony\Component\HttpFoundation\Request;

class SessionStorageTestController extends Controller
{
    /**
     * @Route("/session")
     * @Template()
     */
    public function sessionAction(Request $request)
    {

        $config = $this->getConfig();
        $config->handleRequest($request);

        return array(
            'config' => $config
        );
    }

    public function getConfig()
    {
        /**
         * @var SortingManager $manager
         */
        $manager = $this->get('nvbooster_sortingmanager');

        $config = $manager->createConfig('cookiesort', array(
            'storage' => 'session',
            'sort_columns_count' => 2
        ));

        $config
            ->addColumn('name', 'e.name')
            ->addColumn('birthdate', 'e.birthdate')
            ->addColumn('state', 'e.state')
            ->setDefaultSorting(array(
                'name' => 1,
                'birthdate' => 1
            ))
            ->register();

        return $config;
    }
}
