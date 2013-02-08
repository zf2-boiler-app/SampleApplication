<?php
return array(
	'router' => include 'module.config.routes.php',

	'paths' => array(
    	'avatarsPath' => getcwd().'/data/avatars'
    ),
	'controllers' => array(
        'invokables' => array(
            'User\Controller\User' => 'User\Controller\UserController',
        	'User\Controller\UserAccount' => 'User\Controller\UserAccountController',
        ),
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
    		'User\Controller\User' => array(
    			'login' => array('js' => array('js/User/Controller/UserLoginController.js')),
    			'register' => array('js' => array('js/User/Controller/UserRegisterController.js')),
    		),
    		'User\Controller\UserAccount' => array(
    			'js' => array('js/User/Controller/UserAccountController.js')
    		)
    	)
	),
	'authentication' => array(
		'storage' => 'UserAuthenticationStorage',
		'adapters' => array(
			'LocalAuth' => 'AuthenticationDbTableAdapter',
			'HybridAuth' => 'AuthenticationHybridAuthAdapter'
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
			'AuthenticationDbTableAdapter' => '\User\Factory\AuthenticationDbTableAdapterFactory',
			'AuthenticationHybridAuthAdapter' => '\User\Factory\AuthenticationHybridAuthAdapterFactory',
			'UserService' => '\User\Factory\UserServiceFactory',
			'UserAccountService' => '\User\Factory\UserAccountServiceFactory',
			'UserAuthenticationStorage' => '\User\Factory\UserAuthenticationStorageFactory',
			'UserAuthenticationService' => '\User\Factory\UserAuthenticationServiceFactory',
			'UserModel' => '\User\Factory\UserModelFactory',
			'UserProviderModel' => '\User\Factory\UserProviderModelFactory',
			'SessionManager' => '\User\Factory\SessionManagerFactory',
			'ChangeAvatarForm' => '\User\Factory\ChangeAvatarFormFactory',
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
				$aConfiguration = $oServiceManager->getServiceLocator()->get('Config');
				if(!isset($aConfiguration['paths']['avatarsPath']))throw new \Exception('Avatars path configuration is undefined');
				$oUserAvavatarHelper = new \User\View\Helper\UserAvatarHelper();
				return $oUserAvavatarHelper->setAvatarsPath($aConfiguration['paths']['avatarsPath']);
			}
		)
	)
);