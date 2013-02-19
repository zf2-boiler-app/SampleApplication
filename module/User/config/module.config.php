<?php
return array(
	'router' => include 'module.config.routes.php',
	'asset_bundle' => include 'module.config.assets.php',
	'paths' => array(
    	'avatarsPath' => getcwd().'/data/avatars'
    ),
	'controllers' => array(
        'invokables' => array(
            'User\Controller\User' => 'User\Controller\UserController',
        	'User\Controller\UserAccount' => 'User\Controller\UserAccountController'
        )
    ),
	// Doctrine config
	'doctrine' => array(
		'driver' => array(
			'user_driver' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(__DIR__ . '/../src/User/Entity')
			),
			'orm_default' => array(
				'drivers' => array(
					'User\Entity' => 'user_driver'
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
			'UserAccountService' => '\User\Factory\UserAccountServiceFactory',
			'UserModel' => '\User\Factory\UserModelFactory',
			'UserProviderModel' => '\User\Factory\UserProviderModelFactory',
			'ChangeAvatarForm' => '\User\Factory\ChangeAvatarFormFactory',
			'ChangeEmailForm' => '\User\Factory\ChangeEmailFormFactory',
			'ChangePasswordForm' => '\User\Factory\ChangePasswordFormFactory',
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
				if(!isset($aConfiguration['paths']['avatarsPath']))throw new \LogicException('Avatars path configuration is undefined');
				$oUserAvavatarHelper = new \User\View\Helper\UserAvatarHelper();
				return $oUserAvavatarHelper->setAvatarsPath($aConfiguration['paths']['avatarsPath']);
			}
		)
	)
);