<?php

namespace Frontend\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Knp\Menu\Renderer\ListRenderer;
use Symfony\Component\DependencyInjection\ContainerAware;

class DefaultMenuBuilder extends ContainerAware
{

   protected $factory;
       
    /**
    * @param \Knp\Menu\FactoryInterface $factory
    */
   public function __construct(FactoryInterface $factory)
   {
       $this->factory = $factory;
   }
    
    public function createAdminMenu(Request $request)
    {   
        $menu = $this->factory->createItem('root');
        
	
        //$menu->addChild('Menü', array('uri' => '/admin_bundle/menu'));
        $menu->addChild('Tantárgyak', array('uri' => '/admin/subjects'));
        return $menu;
    }
	
	public function createSidebarMenu(Request $request){
		
		$menu = $this->factory->createItem('sidebar');
		$menu->addChild('Home', array('route' => 'homepage'));
        return $menu;
	}
	
    
	
    protected function addNavLinkURI($menu, $label, $uri)
    {
        $item = $menu->addChild($label, array('uri' => $uri));
        $item->setExtra('translation_domain', $menu->getExtra('translation_domain'));
        $menu->setExtra('request_uri', $menu->getExtra('request_uri'));

        if ($item->getUri() == $menu->getExtra('request_uri')) {
          $item->setAttribute('class', 'active');
        }

        return $item;
    }
}
