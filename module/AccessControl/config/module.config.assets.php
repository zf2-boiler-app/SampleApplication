<?php
return array(
    'assets' => array(
    	'AccessControl\Controller\Registration' => array(
    		'register' => array('js' => array(__DIR__ . '/../assets/js/Controller/RegistrationRegisterController.js')),
    	),
    	'AccessControl\Controller\Authentication' => array(
    		'authenticate' => array('js' => array(__DIR__ . '/../assets/js/Controller/AuthenticationAuthenticateController.js')),
    	)
    )
);