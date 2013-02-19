<?php
namespace AccessControl\InputFilter;
class RegisterInputFilter extends \Zend\InputFilter\InputFilter{
    /**
     * Constructor
     */
    public function __construct(){
    	$this->add(array(
			'name' => 'user_email',
			'required' => true,
			'filters' => array(array('name' => 'StringTrim')),
			'validators' => array(
				array('name'=> 'EmailAddress'),
				array('name'=> 'AccessControl\Validator\EmailAddressAvailabilityValidator')
			)
		))->add(array(
			'name' => 'user_password',
			'required' => true,
			'validators' => array(array('name'=> 'StringLength','options' => array('max'=>32)))
		))->add(array(
			'name' => 'user_confirm_password',
			'required' => true,
			'validators' => array(array('name'=> 'Identical','options' => array('token'=>'user_password')))
		));
    }
}