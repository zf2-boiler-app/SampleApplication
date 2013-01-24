<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'User\Controller\User' => 'User\Controller\UserController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'User' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
            	'options' => array('route' => '/user'),
                'may_terminate' => true,
                'child_routes' => array(
                	'login' => array(
						'type' => 'Zend\Mvc\Router\Http\Segment',
						'options' => array(
							'route' => '/login[/:service][/:redirect]',
							'defaults' => array(
								'controller' => 'User\Controller\User',
								'action'  => 'login'
							)
						)
					),
					'hybridauth' => array(
						'type' => 'Zend\Mvc\Router\Http\Literal',
						'options' => array(
							'route' => '/hybridauth',
							'defaults' => array(
								'controller' => 'User\Controller\User',
								'action' => 'hybridauth'
							)
						)
					),
                	'logout' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                			'route' => '/logout',
                			'defaults' => array(
                				'controller' => 'User\Controller\User',
                				'action' => 'logout'
                			)
                		)
                	),
					'register' => array(
						'type' => 'Zend\Mvc\Router\Http\Segment',
						'options' => array(
							'route' => '/register[/:service]',
							'defaults' => array(
								'controller' => 'User\Controller\User',
								'action'  => 'register'
							)
						)
					),
                	'checkuseremailavailability' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                			'route' => '/checkuseremailavailability',
                			'defaults' => array(
                				'controller' => 'User\Controller\User',
                				'action' => 'checkuseremailavailability'
                			)
                		)
                	),
                	'confirm-email' => array(
                		'type' => 'Zend\Mvc\Router\Http\Segment',
                		'options' => array(
                			'route' => '/confirm-email/:registration_key',
                			'defaults' => array(
                				'controller' => 'User\Controller\User',
                				'action' => 'confirmemail'
                			)
                		)
                	),
                	'forgotten-password' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                			'route' => '/forgotten-password',
                			'defaults' => array(
                				'controller' => 'User\Controller\User',
                				'action' => 'forgottenpassword'
                			)
                		)
                	),
                	'reset-password' => array(
                		'type' => 'Zend\Mvc\Router\Http\Segment',
                		'options' => array(
                			'route' => '/reset-password/:reset_key',
                			'defaults' => array(
                				'controller' => 'User\Controller\User',
                				'action' => 'resetpassword'
                			)
                		)
                	),
                	'resend-confirmation-email' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                			'route' => '/resend-confirmation-email',
                			'defaults' => array(
                				'controller' => 'User\Controller\User',
                				'action' => 'resendconfirmationemail'
                			)
                		)
                	),
                	'account' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                			'route' => '/account',
                			'defaults' => array(
                				'controller' => 'User\Controller\User',
                				'action' => 'account'
                			)
                		)
                	),
                	'delete-account' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                			'route' => '/delete-account',
                			'defaults' => array(
                				'controller' => 'User\Controller\User',
                				'action' => 'deleteaccount'
                			)
                		)
                	),
                	'change-password' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                			'route' => '/change-password',
                			'defaults' => array(
                				'controller' => 'User\Controller\User',
                				'action' => 'changepassword'
                			)
                		)
                	),
                	'change-email' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                			'route' => '/change-email',
                			'defaults' => array(
                				'controller' => 'User\Controller\User',
                				'action' => 'changeemail'
                			)
                		)
                	),
                	'change-avatar' => array(
                		'type' => 'Zend\Mvc\Router\Http\Literal',
                		'options' => array(
                			'route' => '/change-avatar',
                			'defaults' => array(
                				'controller' => 'User\Controller\User',
                				'action' => 'changeavatar'
                			)
                		)
                	)
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
    		'user' => array(
    			'User\Controller\User' => array(
    				'js' => array(
	    				'js/User/Controller/UserController.js'
	    			)
    			)
    		)
    	)
	),
	'hybrid_auth' =>  array(
		'base_url' => "User/hybridauth",

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
				'email/user/confirm-email' => __DIR__ . '/../view/user/email/confirm-email.phtml',
				'email/user/confirm-reset-password' => __DIR__ . '/../view/user/email/confirm-reset-password.phtml',
				'email/user/password-reset' => __DIR__ . '/../view/user/email/password-reset.phtml',
				'email/user/password-changed' => __DIR__ . '/../view/user/email/password-changed.phtml'
			)
		)
	),
    'view_manager' => array(
    	'template_path_stack' => array('User' => __DIR__ . '/../view')
    ),
	'service_manager' => array(
		'factories' => array(
			'UserService' => '\User\Factory\UserServiceFactory',
			'AuthAdapter' => '\User\Factory\AuthAdapterFactory',
			'AuthStorage' => '\User\Factory\AuthStorageFactory',
			'HybridAuthAdapter' => '\User\Factory\HybridAuthAdapterFactory',
			'AuthService' => '\User\Factory\AuthServiceFactory',
			'UserModel' => '\User\Factory\UserModelFactory',
			'UserProviderModel' => '\User\Factory\UserProviderModelFactory',
			'SessionManager' => '\User\Factory\SessionManagerFactory',
			'ChangeEmailForm' => '\User\Factory\ChangeEmailFormFactory',
			'ChangePasswordForm' => '\User\Factory\ChangePasswordFormFactory',
			'LoginForm' => '\User\Factory\LoginFormFactory',
			'RegisterForm' => '\User\Factory\RegisterFormFactory',
			'ResetPasswordForm' => '\User\Factory\ResetPasswordFormFactory',
		)
	),
	'controller_plugins' => array(
       	'invokables' => array(
        	'userMustBeLoggedIn' => 'User\Mvc\Controller\Plugin\UserMustBeLoggedInPlugin',
       	)
    ),
	'view_helpers' => array(
		'factories' => array(
			'userAvatar' => function(\Zend\ServiceManager\ServiceManager $oServiceManager){
				$oUserAvavatarHelper = new \User\View\Helper\UserAvatarHelper();
				return $oUserAvavatarHelper->setAvatarsPath(getcwd().'/data/avatars');
			}
		)
	)
);