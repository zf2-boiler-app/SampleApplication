<?php
return array(
    'assets' => array(
    	'User\Controller\User' => array(
    		'login' => array('js' => array(__DIR__ . '/../assets/js/Controller/UserLoginController.js')),
    		'register' => array('js' => array(__DIR__ . '/../assets/js/Controller/UserRegisterController.js')),
    	),
    	'User\Controller\UserAccount' => array(
    		'js' => array(__DIR__ . '/../assets/js/Controller/UserAccountController.js')
    	)
    )
);