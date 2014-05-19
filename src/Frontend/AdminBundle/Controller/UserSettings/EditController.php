<?php

namespace Frontend\AdminBundle\Controller\UserSettings;

use Admingenerated\FrontendAdminBundle\BaseUserSettingsController\EditController as BaseEditController;

/**
 * EditController
 */
class EditController extends BaseEditController
{
    
    public function preSave(\Symfony\Component\Form\Form $form, \Frontend\AccountBundle\Entity\UserSettings $UserSettings)
    {
        if($form->get('avatarId')->getData() == '0'){
             $UserSettings->setAvatar(null);
        }
    }

}
