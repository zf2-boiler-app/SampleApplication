<?php
namespace AccessControl\InputFilter;
class ResetCredentialInputFilter extends \Zend\InputFilter\InputFilter{
	/**
	 * Constructor
	 */
    public function __construct(){
    	$this->add(array(
			'name' => 'auth_access_identity',
			'required' => true
		));
    }
}