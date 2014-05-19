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

        $menu->addChild('Tantárgyak', array('route' => 'admin_page_subjects_u'));
        $menu->addChild('Felhasználói beállítások', array('route' => 'admin_page_UserSettings_u'));
        $menu->addChild('Iskolák', array('route' => 'admin_page_school_u'));
        $menu->addChild('Dokumentumok', array('route' => 'admin_page_docs_u'));
        $menu->addChild('Fájlok', array('route' => 'admin_page_file_u'));
       
        return $menu;
    }
    
}
