<?php

namespace Frontend\AccountBundle\Form\Type;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Date;

class BaseSettingsFormType extends AbstractType{
    
    
    private $user;
    private $userSettings;
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->userSettings = $this->user->getUserSettings();
        
        $builder->add('name', 'text', array(
            'label' => 'Név',
            'data' => $this->userSettings == null ? '' : $this->userSettings->getName()
        ));
        
        $builder->add('username', 'text', array(
            'data' => $this->user->getUserName(),
            'read_only' => true,
            'label' => 'Nicknév'
        ));
        
        $builder->add('email', 'text', array(
            'data' => $this->user->getEmail(),
            'read_only' => true,
            'label' => 'Email cím'
        ));
        
        
        $builder->add('birthDay', 'date', array(
                'required' => false,
                    'data' => ( null != $this->userSettings && $this->userSettings->getBirthDate() != null) ?  $this->userSettings->getBirthDate() : null,
                    'empty_value' => array('year' => 'év', 'month' => 'hó', 'day' => 'nap'),
                    'required' => false,
                    'label' => 'Születési dátum:', 'years' => range(1930,2013),
                    'attr' => array('class' => 'date-select'))
                );
	
        $builder->add('gender', 'choice', array(
           'label' => 'Nem',
           'attr' => array('class'=> 'radio-buttons'),
           'data' => ( null != $this->userSettings && $this->userSettings->getGender() != null) ?  $this->userSettings->getGender() : null,
           'choices' => array('male' => 'férfi', 'female' => 'nő'),
           'expanded' => true, 'multiple' => false
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
        return 'settings_base_form';
    }

    public function setUser($user){
        $this->user = $user;
    }
    
}
