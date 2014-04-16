<?php

namespace Frontend\AccountBundle\Form\Type;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Date;

class CommentSettingsFormType extends AbstractType {
    
    private $user;
    private $userSettings;
    
    public function buildForm(FormBuilderInterface $builder, array $options){
        
        $builder->add('commentText', 'textarea', array(
            'required' => false,
           'label' => 'Megjegyzés az adminokhoz',
           'data' =>  ( null != $this->userSettings && $this->userSettings->getCommentText() != null) ?  $this->userSettings->getCommentText() : null,
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
       return 'safety_settings'; 
    }
    
    public function setUser($user){
        $this->user = $user;
    }
    
    public function setUserSettings($userSettings){
        $this->userSettings = $userSettings;
    }
}
