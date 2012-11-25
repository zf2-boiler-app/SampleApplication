<?php
namespace ZF2User\Form;
class Login extends \Zend\Form\Form{
    /**
     * Constructor
     */
	public function __construct(){
        parent::__construct('login');
        $this->setAttribute('method', 'post')
        ->add(array(
            'name' => 'username',
            'attributes' => array('type' => 'text'),
            'options' => array('label' => 'username_or_email')
        ))
        ->add(array(
            'name' => 'password',
            'attributes' => array('type'  => 'password'),
            'options' => array('label' => 'password')
        ))
        ->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
            	'value' => 'Sign in'
            )
        ));
    }
}