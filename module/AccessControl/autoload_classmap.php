<?php
return array(
	//Controllers
	'AccessControl\Controller\RegistrationController' => __DIR__.'/src/AccessControl/Controller/RegistrationController.php',

	//Factories
	'AccessControl\Factory\AccessControlAuthenticationServiceFactory' => __DIR__.'/src/AccessControl/Factory/AccessControlAuthenticationServiceFactory.php',
	'AccessControl\Factory\AuthenticationStorageFactory' => __DIR__.'/src/AccessControl/Factory/AuthenticationStorageFactory.php',
	'AccessControl\Factory\RegisterFormFactory' => __DIR__.'/src/AccessControl/Factory/RegisterFormFactory.php',

	//Forms
	'AccessControl\Form\RegisterForm' => __DIR__.'/src/AccessControl/Form/RegisterForm.php',

	//Input filters
	'AccessControl\InputFilter\RegisterInputFilter' => __DIR__.'/src/AccessControl/InputFilter/RegisterInputFilter.php',

	//Module
	'AccessControl\Module' => __DIR__.'/Module.php',

	//Services
	'AccessControl\Authentication\AccessControlAuthenticationService' => __DIR__.'/src/AccessControl/Authentication/AccessControlAuthenticationService.php',

	//Validators
	'AccessControl\Validator\EmailAddressAvailabilityValidator' => __DIR__.'/src/AccessControl/Validator/EmailAddressAvailabilityValidator.php'
);