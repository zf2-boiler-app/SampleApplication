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
							'action' => 'checkUsernameIdentityAvailability'
						)
					)
				),
				'ConfirmEmail' => array(
					'type' => 'Zend\Mvc\Router\Http\Segment',
					'options' => array(
						'route' => '/confirm-email/:public_key/:email_identity',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Registration',
							'action' => 'confirmEmail'
						)
					)
				),
				'ResendConfirmationEmail' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/resend-confirmation-email',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Registration',
							'action' => 'resendConfirmationEmail'
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
				'ForgottenCredential' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/forgotten-credential',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Authentication',
							'action' => 'forgottenCredential'
						)
					)
				),
				'ResetCredential' => array(
					'type' => 'Zend\Mvc\Router\Http\Segment',
					'options' => array(
						'route' => '/reset-credential/:reset_key',
						'defaults' => array(
							'controller' => 'AccessControl\Controller\Authentication',
							'action' => 'resetCredential'
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