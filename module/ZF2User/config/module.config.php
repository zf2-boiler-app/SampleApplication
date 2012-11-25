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
						'type' => 'Literal',
						'options' => array(
							'route' => '/login',
							'defaults' => array(
								'controller' => 'ZF2User\Controller\User',
								'action'  => 'login'
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
    'view_manager' => array(
    	'template_path_stack' => array('ZF2User' => __DIR__ . '/../view')
    ),
	'service_manager' => array(
		'factories' => array(
			'UserService' => function(\Zend\ServiceManager\ServiceManager $oServiceManager){
				$oUserService = new \ZF2User\Service\UserService();
				return $oUserService->setServiceManager($oServiceManager);
			}
		)
	)
);