<?php

namespace nvbooster\TestingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use nvbooster\SortingManager\SortingManager;
use Symfony\Component\HttpFoundation\Request;

class TwigExtensionTestController extends Controller
{
    /**
     * @Route("/twig")
     * @Template()
     */
    public function twigAction(Request $request)
    {

        $config = $this->getConfig();
        $config->handleRequest($request);


        return array(
            'control' => $config->createControl()
        );
    }

    public function getConfig()
    {
        /**
         * @var SortingManager $manager
         */
        $manager = $this->get('nvbooster_sortingmanager');

        $config = $manager->createConfig('testsort', array(
            'storage' => 'session',
            'sort_columns_count' => 2
        ));

        $config
            ->addColumn('name', 'e.name', array(
                'label' => 'Person`s name'
            ))
            ->addColumn('birthdate', 'e.birthdate', array(
                'label' => 'p_birthdate'
            ))
            ->addColumn('state', 'e.state', array(
                'translation_domain' => 'testdomain'
            ))
            ->setDefaultSorting(array(
                'name' => 1,
                'birthdate' => -1
            ))
            ->register();

        return $config;
    }
}
