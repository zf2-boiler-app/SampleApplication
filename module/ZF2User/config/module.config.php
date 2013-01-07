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
							'route' => '/login[/:service][/:redirect]',
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
                	'confirm-email' => array(
                		'type' => 'Zend\Mvc\Router\Http\Segment',
                		'options' => array(
                			'route' => '/confirm-email/:registration_key',
                			'defaults' => array(
                				'controller' => 'ZF2User\Controller\User',
                				'action' => 'confirmemail'
                			)
                		)
                	),
                	'forgotten-password' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                			'route' => '/forgotten-password',
                			'defaults' => array(
                				'controller' => 'ZF2User\Controller\User',
                				'action' => 'forgottenpassword'
                			)
                		)
                	),
                	'reset-password' => array(
                		'type' => 'Zend\Mvc\Router\Http\Segment',
                		'options' => array(
                			'route' => '/reset-password/:reset_key',
                			'defaults' => array(
                				'controller' => 'ZF2User\Controller\User',
                				'action' => 'resetpassword'
                			)
                		)
                	),
                	'resend-confirmation-email' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                			'route' => '/resend-confirmation-email',
                			'defaults' => array(
                				'controller' => 'ZF2User\Controller\User',
                				'action' => 'resendconfirmationemail'
                			)
                		)
                	),
                	'account' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                			'route' => '/account',
                			'defaults' => array(
                				'controller' => 'ZF2User\Controller\User',
                				'action' => 'account'
                			)
                		)
                	),
                	'delete-account' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                			'route' => '/delete-account',
                			'defaults' => array(
                				'controller' => 'ZF2User\Controller\User',
                				'action' => 'deleteaccount'
                			)
                		)
                	),
                	'change-password' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                			'route' => '/change-password',
                			'defaults' => array(
                				'controller' => 'ZF2User\Controller\User',
                				'action' => 'changepassword'
                			)
                		)
                	),
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
	'messenger' => array(
		'view_manager' => array(
			'template_map' => array(
				'email/user/confirm-email' => __DIR__ . '/../view/zf2-user/email/confirm-email.phtml',
				'email/user/confirm-reset-password' => __DIR__ . '/../view/zf2-user/email/confirm-reset-password.phtml',
				'email/user/password-reset' => __DIR__ . '/../view/zf2-user/email/password-reset.phtml'
			)
		)
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
			'ChangePasswordForm' => '\ZF2User\Factory\ChangePasswordFormFactory',
			'LoginForm' => '\ZF2User\Factory\LoginFormFactory',
			'RegisterForm' => '\ZF2User\Factory\RegisterFormFactory',
			'ResetPasswordForm' => '\ZF2User\Factory\ResetPasswordFormFactory',
		)
	),
	'controller_plugins' => array(
       	'invokables' => array(
        	'userMustBeLoggedIn' => 'ZF2User\Mvc\Controller\Plugin\UserMustBeLoggedInPlugin',
       	)
    )
);