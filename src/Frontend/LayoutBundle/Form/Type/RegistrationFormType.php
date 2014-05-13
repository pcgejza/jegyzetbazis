<?php

namespace Frontend\LayoutBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('name', 'text', array(
           'label' => 'Név*',
           'required' => false,
           'mapped' => false,
           'attr' => array(
               'backtext' => 'Add meg a neved...',
               'class' => 'name')
        ));
        
        $builder->add('email','text', array(
            'label' => 'Email cím',
           'required' => false,
           'attr' => array('backtext' => 'Add meg az email címed...',
               'class' => 'email')
        ));
        
        $builder->add('password', 'repeated', array(
            'type' => 'password',
           'required' => false,
            'first_options'  => array('label' => 'Jelszó*'),
            'second_options' => array('label' => 'Jelszó újra*'),
           'attr' => array('class' => 'password')
        ));
        
        $builder->add('nickname','text', array(
            'label' => 'Nicknév',
           'required' => false,
            'mapped' => false,
            'attr' => array('backtext' => 'Válassz egy nicknevet',
               'class' => 'nickname')
        ));
        
        $builder->add('school','text', array(
            'label' => 'Milyen egyetemre/középiskolába jársz?',
            'attr' => array('class' => 'school-area'),
           'required' => false,
            'mapped' => false
        ));
        
    }

    public function getName()
    {
        return 'frontend_user_registration';
    }
}