<?php

namespace Frontend\AccountBundle\Form\Type;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Date;

class SafetySettingsFormType extends AbstractType {
    
    private $user;
    private $userSettings;
    
    public function buildForm(FormBuilderInterface $builder, array $options){
        
        $Choices = array(
          'all' => 'Mindenki',
          'only_users' => 'Csak regisztrált felhasználók',
          'only_friends' => 'Csak barátok'
        );
        
        $builder->add('myUploadsVisit', 'choice', array(
           'required' => false,
           'label' => 'Kik láthaták a feltöltéseimet?',
           'empty_value' => 'Válassz!',
           'data' =>  ( null != $this->userSettings && $this->userSettings->getMyUploadsVisit() != null) ?  $this->userSettings->getMyUploadsVisit() : null,
           'choices' => $Choices,
           'expanded' => false, 'multiple' => false
        ));
        
        $builder->add('myProfileVisit', 'choice', array(
            'required' => false,
           'label' => 'Kik láthaták a profil adataimat?',
           'empty_value' => 'Válassz!',
           'data' =>  ( null != $this->userSettings && $this->userSettings->getMyProfileVisit() != null) ?  $this->userSettings->getMyProfileVisit() : null,
           'choices' => $Choices,
           'expanded' => false, 'multiple' => false
        ));
        
        $builder->add('messageToMe', 'choice', array(
            'required' => false,
           'label' => 'Kik küldhetnek nekem üzenetet?',
           'empty_value' => 'Válassz!',
           'data' =>  ( null != $this->userSettings && $this->userSettings->getMessageToMe() != null) ?  $this->userSettings->getMessageToMe() : null,
            'choices' => $Choices,
           'expanded' => false, 'multiple' => false
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
