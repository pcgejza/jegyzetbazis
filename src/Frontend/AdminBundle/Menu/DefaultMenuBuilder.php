<?php

namespace Frontend\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Knp\Menu\Renderer\ListRenderer;
use Symfony\Component\DependencyInjection\ContainerAware;

class DefaultMenuBuilder extends ContainerAware
{

    public function adminMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'frontend_index_homepage'));
        $menu->addChild('About Me', array(
            'route' => 'frontend_index_homepage',
            'routeParameters' => array('id' => 42)
        ));
        // ... add more children

        return $menu;
    }
    
}
