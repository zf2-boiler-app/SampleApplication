<?php
return array(
    'assets' => array(
    	'AccessControl\Controller\Registration' => array(
    		'register' => array('js' => array(
    			__DIR__ . '/../assets/js/Validator/auth-access-identities.js',
    			__DIR__ . '/../assets/js/Controller/RegistrationRegisterController.js',
    			'@zfRootPath/vendor/nak5ive/Form.PasswordStrength/Source/Form.PasswordStrength.js',
    			__DIR__ . '/../assets/js/Behavior/Form.PasswordStrength.js'
    		)),
    	),
    	'AccessControl\Controller\Authentication' => array(
    		'authenticate' => array('js' => array(__DIR__ . '/../assets/js/Controller/AuthenticationAuthenticateController.js')),
    	)
    )
);