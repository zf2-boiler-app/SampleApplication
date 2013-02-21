<?php
return array(
	'router' => include 'module.config.routes.php',
	'asset_bundle' => include 'module.config.assets.php',
	'doctrine' => include 'module.config.doctrine.php',
	'translator' => include 'module.config.translations.php',
	'messenger' => array(
		'view_manager' => array(
			'template_map' => array(
				'email/registration/confirm-email' => __DIR__ . '/../view/email/registration/confirm-email.phtml',
				'email/authentication/confirm-reset-password' => __DIR__ . '/../view/email/authentication/confirm-reset-password.phtml',
				'email/authentication/password-reset' => __DIR__ . '/../view/email/authentication/password-reset.phtml'
			)
		)
	),
	'authentication' => array(
		'storage' => 'AuthenticationStorage',
		'adapters' => array(
			'LocalAuth' => 'AuthenticationDoctrineAdapter',
			'HybridAuth' => 'AuthenticationHybridAuthAdapter'
		)
	),
	'hybrid_auth' =>  array(
		'base_url' => 'AccessControl/hybridauth',

		'providers' => array(
			//Set Redirect URIs = "http://xxxxx/access-control/hybridauth?hauth.done=Google" in google APIs console
			'Google' => array(
				'enabled' => true,
				'keys' => array('id' => '','secret' => ''),
				'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
				'access_type' => 'online',
				'approval_prompt' => 'force'
			),
			'Facebook' => array(
				'enabled' => true,
				'keys' => array( 'id' => '', 'secret' => ''),
				'scope' => array( 'email, user_about_me, offline_access')
			),
			'Twitter' => array(
				'enabled' => true,
				'keys' => array('key' => '', 'secret' => '')
			)
		),
		'debug_mode' => false
	),
	'controllers' => array(
		'invokables' => array(
			'AccessControl\Controller\Registration' => 'AccessControl\Controller\RegistrationController',
			'AccessControl\Controller\Authentication' => 'AccessControl\Controller\AuthenticationController'
		)
	),
	'service_manager' => array(
		'invokables' => array(
			'AccessControlService' => 'AccessControl\Service\AccessControlService',
			'RegistrationService' => 'AccessControl\Service\RegistrationService'
		),
		'factories' => array(
			'AccessControlAuthenticationService' => 'AccessControl\Factory\AccessControlAuthenticationServiceFactory',
			'AuthenticationStorage' => 'AccessControl\Factory\AuthenticationStorageFactory',
			'AuthenticationDoctrineAdapter' => 'AccessControl\Factory\AuthenticationDoctrineAdapterFactory',
			'AuthenticationHybridAuthAdapter' => 'AccessControl\Factory\AuthenticationHybridAuthAdapterFactory',
			'RegisterForm' => 'AccessControl\Factory\RegisterFormFactory'
		)
	),
	'view_manager' => array(
		'template_path_stack' => array('AccessControl' => __DIR__ . '/../view')
	)
);