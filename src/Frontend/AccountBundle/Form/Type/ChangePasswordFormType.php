<?php

namespace Frontend\AccountBundle\Form\Type;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Date;

class ChangePasswordFormType extends AbstractType {
    
    
    public function buildForm(FormBuilderInterface $builder, array $options){
        
        $builder->add('pass1', 'password', array(
            'required' => false,
            'attr' => array('class' => 'pass1'),
           'label' => 'Jelszó*',
         ));
        $builder->add('pass2', 'password', array(
            'required' => false,
            'attr' => array('class' => 'pass2'),
           'label' => 'Jelszó újra*',
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
       return 'password_settings'; 
    }
    
}
