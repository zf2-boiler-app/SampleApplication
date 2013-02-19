<?php
return array(
	'router' => include 'module.config.routes.php',
	'asset_bundle' => include 'module.config.assets.php',
	'doctrine' => array(
		'driver' => array(
			'accesscontrol_driver' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(__DIR__ . '/../src/AccessControl/Entity')
			),
			'orm_default' => array(
				'drivers' => array(
					'AccessControl\Entity' => 'accesscontrol_driver'
				)
			)
		)
	),
	'translator' => array(
		'translation_file_patterns' => array(
			array(
				'type' => 'phparray',
				'base_dir' => __DIR__ . '/../languages',
				'pattern'  => '%s/Common.php'
			),
			array(
				'type' => 'phparray',
				'base_dir' => __DIR__ . '/../languages',
				'pattern'  => '%s/Validate.php',
				'text_domain' => 'validator'
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
	),
);