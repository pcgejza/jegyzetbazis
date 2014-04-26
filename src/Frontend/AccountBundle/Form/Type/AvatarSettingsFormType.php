<?php

namespace Frontend\AccountBundle\Form\Type;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Date;

use Gregwar\Image\Image;

class AvatarSettingsFormType extends AbstractType{
    
    
    private $user;
    private $avatar;
    private $userSettings;
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
                
        $builder->add('avatar', 'hidden', array(
            'data' => ($this->userSettings->getAvatar() !== NULL) ? $this->userSettings->getAvatar()->getWebPath() : "",
            'required' => false,
        ));
        
        $builder->add('avatarId', 'hidden', array(
            'attr' => array('class' => 'selectedAvatarId'),
            'data' => ($this->userSettings->getAvatar() !== NULL) ? $this->userSettings->getAvatar()->getId() : 0,
            'required' => false,
        ));
        
        $builder->add('password', 'password', array(
            'data' => '',
            'required' => true,
            'label' => 'Add meg a jelenlegi jelszavad'
        ));
        
        $builder->add('send', 'submit', array(
           'label' => 'Mentés' 
        ));
        
    }

    
    public function getName() {
        return 'settings_avatar_form';
    }

    public function setUser($user){
        $this->user = $user;
    }
    
    public function setAvatar($avatar){
        $this->avatar = $avatar;
    }
    
    public function setUserSettings($userSettings){
        $this->userSettings = $userSettings;
    }
    
}
