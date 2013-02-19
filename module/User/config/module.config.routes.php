<?php
return array(
	'routes' => array(
		'User' => array(
			'type' => 'Zend\Mvc\Router\Http\Literal',
			'options' => array(
				'route' => '/user',
				'defaults' => array(
					'controller' => 'User\Controller\User',
					'action' => 'index'
				)
			),
			'may_terminate' => true,
			'child_routes' => array(
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