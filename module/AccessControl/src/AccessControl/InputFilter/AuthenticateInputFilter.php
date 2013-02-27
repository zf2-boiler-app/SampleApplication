<?php
namespace AccessControl\InputFilter;
class AuthenticateInputFilter extends \Zend\InputFilter\InputFilter{
	/**
	 * Constructor
	 */
    public function __construct(){
    	$this->add(array(
			'name' => 'auth_access_identity',
			'required' => true
		))->add(array(
			'name' => 'auth_access_credential',
			'required' => true
		));
    }
}