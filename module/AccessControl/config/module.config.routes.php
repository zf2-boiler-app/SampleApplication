<?php
return array(
	'routes' => array(
		'AccessControl' => array(
			'type' => 'Zend\Mvc\Router\Http\Literal',
			'options' => array('route' => '/access-control'),
			'may_terminate' => true,
			'child_routes' => array(
				'register' => array(
					'type' => 'Zend\Mvc\Router\Http\Segment',
					'options' => array(
						'route' => '/register[/:service]',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Registration',
							'action'  => 'register'
						)
					)
				),
				'checkuseremailavailability' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/checkuseremailavailability',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Registration',
							'action' => 'checkuseremailavailability'
						)
					)
				),
				'confirm-email' => array(
					'type' => 'Zend\Mvc\Router\Http\Segment',
					'options' => array(
						'route' => '/confirm-email/:registration_key',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Registration',
							'action' => 'confirmemail'
						)
					)
				),
				'resend-confirmation-email' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/resend-confirmation-email',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Registration',
							'action' => 'resendconfirmationemail'
						)
					)
				),
				'authenticate' => array(
					'type' => 'Zend\Mvc\Router\Http\Segment',
					'options' => array(
						'route' => '/authenticate[/:service][/:redirect]',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Authentication',
							'action'  => 'authenticate'
						)
					)
				),
				'hybridauth' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/hybridauth',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Authentication',
							'action' => 'hybridauth'
						)
					)
				),
				'forgotten-password' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/forgotten-password',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Authentication',
							'action' => 'forgottenpassword'
						)
					)
				),
				'reset-password' => array(
					'type' => 'Zend\Mvc\Router\Http\Segment',
					'options' => array(
						'route' => '/reset-password/:reset_key',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Authentication',
							'action' => 'resetpassword'
						)
					)
				),
				'logout' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/logout',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Authentication',
							'action' => 'logout'
						)
					)
				)
			)
		)
	)
);