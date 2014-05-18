<?php

namespace Frontend\AdminBundle\Controller\School;

use Admingenerated\FrontendAdminBundle\BaseSchoolController\NewController as BaseNewController;

/**
 * NewController
 */
class NewController extends BaseNewController
{
    public function preSave(\Symfony\Component\Form\Form $form, \Frontend\AccountBundle\Entity\School $School){
        $School->setCreatorUser($this->get('security.context')->getToken()->getUser());
        $School->setCreatedAt(new \DateTime('now')); 
    }
}
