<?php
return array(
	'routes' => array(
		'AccessControl' => array(
			'type' => 'Zend\Mvc\Router\Http\Literal',
			'options' => array('route' => '/access-control'),
			'may_terminate' => true,
			'child_routes' => array(
				'Register' => array(
					'type' => 'Zend\Mvc\Router\Http\Segment',
					'options' => array(
						'route' => '/register[/:service]',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Registration',
							'action'  => 'register'
						)
					)
				),
				'CheckEmailIdentityAvailability' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/check-email-identity-availability',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Registration',
							'action' => 'checkEmailIdentityAvailability'
						)
					)
				),
				'CheckUsernameIdentityAvailability' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/check-username-identity-availability',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Registration',
							'action' => 'checkusernameidentityavailability'
						)
					)
				),
				'ConfirmEmail' => array(
					'type' => 'Zend\Mvc\Router\Http\Segment',
					'options' => array(
						'route' => '/confirm-email/:email_identity/:public_key',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Registration',
							'action' => 'confirmemail'
						)
					)
				),
				'ResendConfirmationEmail' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/resend-confirmation-email',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Registration',
							'action' => 'resendconfirmationemail'
						)
					)
				),
				'Authenticate' => array(
					'type' => 'Zend\Mvc\Router\Http\Segment',
					'options' => array(
						'route' => '/authenticate[/:service][/:redirect]',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Authentication',
							'action'  => 'authenticate'
						)
					)
				),
				'HybridAuth' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/hybridauth',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Authentication',
							'action' => 'hybridauth'
						)
					)
				),
				'ForgottenPassword' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/forgotten-password',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Authentication',
							'action' => 'forgottenpassword'
						)
					)
				),
				'ResetPassword' => array(
					'type' => 'Zend\Mvc\Router\Http\Segment',
					'options' => array(
						'route' => '/reset-password/:reset_key',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Authentication',
							'action' => 'resetpassword'
						)
					)
				),
				'Logout' => array(
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