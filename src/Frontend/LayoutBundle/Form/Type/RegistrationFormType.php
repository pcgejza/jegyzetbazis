<?php

namespace Frontend\LayoutBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('name','text', array(
            'label' => 'Név'
        ));
    }

    public function getName()
    {
        return 'frontend_user_registration';
    }
}