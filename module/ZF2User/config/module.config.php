<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'ZF2User\Controller\User' => 'ZF2User\Controller\UserController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'zf2user' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
            	'options' => array('route' => '/user'),
                'may_terminate' => true,
                'child_routes' => array(
                	'login' => array(
						'type' => 'Zend\Mvc\Router\Http\Segment',
						'options' => array(
							'route' => '/login[/:service]',
							'defaults' => array(
								'controller' => 'ZF2User\Controller\User',
								'action'  => 'login'
							)
						)
					),
					'hybridauth' => array(
						'type' => 'Zend\Mvc\Router\Http\Literal',
						'options' => array(
							'route' => '/hybridauth',
							'defaults' => array(
								'controller' => 'ZF2User\Controller\User',
								'action' => 'hybridauth'
							)
						)
					),
                	'logout' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                			'route' => '/logout',
                			'defaults' => array(
                				'controller' => 'ZF2User\Controller\User',
                				'action' => 'logout'
                			)
                		)
                	),
					'register' => array(
						'type' => 'Zend\Mvc\Router\Http\Segment',
						'options' => array(
							'route' => '/register[/:service]',
							'defaults' => array(
								'controller' => 'ZF2User\Controller\User',
								'action'  => 'register'
							)
						)
					),
                	'checkuseremailavailability' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                			'route' => '/checkuseremailavailability',
                			'defaults' => array(
                				'controller' => 'ZF2User\Controller\User',
                				'action' => 'checkuseremailavailability'
                			)
                		)
                	),
                )
            )
        )
    ),
	'asset_bundle' => array(
    	'assets' => array(
    		'zf2user' => array(
    			'ZF2User\Controller\User' => array(
    				'js' => array(
	    				'js/Zf2User/Controller/UserController.js',
	    			)
    			)
    		)
    	)
	),
	'hybrid_auth' =>  array(
		'base_url' => "zf2user/hybridauth",

		'providers' => array(
			//Set Redirect URIs = "http://xxxxx/user/hybridauth?hauth.done=Google" in google APIs console
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

    'view_manager' => array(
    	'template_path_stack' => array('ZF2User' => __DIR__ . '/../view')
    ),
	'service_manager' => array(
		'factories' => array(
			'UserService' => '\ZF2User\Factory\UserServiceFactory',
			'AuthAdapter' => '\ZF2User\Factory\AuthAdapterFactory',
			'AuthStorage' => '\ZF2User\Factory\AuthStorageFactory',
			'HybridAuthAdapter' => '\ZF2User\Factory\HybridAuthAdapterFactory',
			'AuthService' => '\ZF2User\Factory\AuthServiceFactory',
			'UserModel' => '\ZF2User\Factory\UserModelFactory',
			'UserProviderModel' => '\ZF2User\Factory\UserProviderModelFactory',
			'SessionManager' => '\ZF2User\Factory\SessionManagerFactory',
			'LoginForm' => '\ZF2User\Factory\LoginFormFactory',
			'RegisterForm' => '\ZF2User\Factory\RegisterFormFactory'
		)
	)
);