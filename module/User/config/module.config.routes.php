<?php
return array(
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
							'controller' => 'User\Controller\UserAccount',
							'action' => 'account'
						)
					)
				),
				'delete-account' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/delete-account',
						'defaults' => array(
							'controller' => 'User\Controller\UserAccount',
							'action' => 'deleteaccount'
						)
					)
				),
				'change-password' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/change-password',
						'defaults' => array(
							'controller' => 'User\Controller\UserAccount',
							'action' => 'changepassword'
						)
					)
				),
				'change-email' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/change-email',
						'defaults' => array(
							'controller' => 'User\Controller\UserAccount',
							'action' => 'changeemail'
						)
					)
				),
				'change-avatar' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/change-avatar',
						'defaults' => array(
							'controller' => 'User\Controller\UserAccount',
							'action' => 'changeavatar'
						)
					)
				)
			)
		)
	)
);