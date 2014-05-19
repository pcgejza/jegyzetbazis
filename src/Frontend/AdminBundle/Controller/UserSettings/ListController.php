<?php

namespace Frontend\AdminBundle\Controller\UserSettings;

use Admingenerated\FrontendAdminBundle\BaseUserSettingsController\ListController as BaseListController;

/**
 * ListController
 */
class ListController extends BaseListController
{
    protected function getQuery(){
        $query = $this->buildQuery();

        $this->processQuery($query);
        $this->processSort($query);
        $this->processFilters($query);
        $this->processScopes($query);

        // hozzájoinolni a tantárgyakat
        $query = $query->select('q, u, avatar')
                 ->leftJoin('q.user', 'u')
                 ->leftJoin('q.avatar', 'avatar');
        
        
        return $query->getQuery();
    }
}
