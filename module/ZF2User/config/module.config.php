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
                'type' => 'Literal',
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
						'type' => 'Literal',
						'options' => array(
							'route' => '/hybridauth',
							'defaults' => array(
								'controller' => 'ZF2User\Controller\User',
								'action' => 'hybridauth'
							)
						)
					),
                	'logout' => array(
                		'type' => 'Literal',
                		'options' => array(
                			'route' => '/logout',
                			'defaults' => array(
                				'controller' => 'ZF2User\Controller\User',
                				'action' => 'logout'
                			)
                		)
                	),
					'register' => array(
						'type' => 'Literal',
						'options' => array(
							'route' => '/register',
							'defaults' => array(
								'controller' => 'ZF2User\Controller\User',
								'action' => 'register',
							)
						)
					)
                )
            )
        )
    ),
	'hybrid_auth' =>  array(
		'base_url' => "zf2user/hybridauth",

		'providers' => array (
			'Google' => array ( // 'id' is your google client id
				'enabled' => true,
				'keys' => array ( 'id' => '', 'secret' => '' ),
			),
			'Facebook' => array ( // 'id' is your facebook application id
				'enabled' => true,
				'keys' => array ( 'id' => '', 'secret' => '' ),
				'scope' => array ( 'email, user_about_me, offline_access' )
			),
			'Twitter' => array ( // 'key' is your twitter application consumer key
				'enabled' => true,
				'keys' => array ( 'key' => '', 'secret' => '' )
			)
		),
		'debug_mode' => false ,
	),

    'view_manager' => array(
    	'template_path_stack' => array('ZF2User' => __DIR__ . '/../view')
    ),
	'service_manager' => array(
		'factories' => array(
			'UserService' => function(\Zend\ServiceManager\ServiceManager $oServiceManager){
				$oUserService = new \ZF2User\Service\UserService();
				return $oUserService->setServiceManager($oServiceManager);
			},
			'AuthAdapter' => function(\Zend\ServiceManager\ServiceManager $oServiceManager){
				return new \Zend\Authentication\Adapter\DbTable(
					$oServiceManager->get('Zend\Db\Adapter\Adapter'),
					$oServiceManager->get('UserModel')->getTable(),
					'user_email',
					'user_password',
					'MD5(?)'
				);
			},
			'AuthStorage' => function(\Zend\ServiceManager\ServiceManager $oServiceManager){
				return new \Zend\Authentication\Storage\Session();
			},
			'HybridAuthAdapter' => '\ZF2User\Factory\HybridAuthAdapterFactory',
			'AuthService' => function(\Zend\ServiceManager\ServiceManager $oServiceManager){
				return new \ZF2User\Authentication\AuthenticationService(
					$oServiceManager->get('AuthStorage'),
					$oServiceManager->get('AuthAdapter')
				);
			},
			'UserModel' => function(\Zend\ServiceManager\ServiceManager $oServiceManager){
				return new \ZF2User\Model\UserModel($oServiceManager->get('Zend\Db\Adapter\Adapter'));
			}
		)
	)
);