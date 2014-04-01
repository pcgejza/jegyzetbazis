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
           'mapped' => false,
           'attr' => array(
               'backtext' => 'Add meg a neved...')
        ));
        
        $builder->add('email','text', array(
            'label' => 'Email cím',
           'attr' => array('backtext' => 'Add meg az email címed...')
        ));
        
        $builder->add('password', 'repeated', array(
            'type' => 'password',
            'first_options'  => array('label' => 'Jelszó*'),
            'second_options' => array('label' => 'Jelszó újra*'),
        ));
        
        $builder->add('nickname','text', array(
            'label' => 'Nicknév',
            'mapped' => false,
            'attr' => array('backtext' => 'Válassz egy nicknevet')
        ));
        
        $builder->add('university','choice', array(
            'label' => 'Melyik egyetemre jársz?',
            'mapped' => false,
            'expanded' => false,
            'multiple' => false
        ));
        
    }

    public function getName()
    {
        return 'frontend_user_registration';
    }
}