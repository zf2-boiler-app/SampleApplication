<?php
return array(
	'messenger' => array(
		'system_user' => array(
			'email' => '',
			'name' => ''
		),
		'view_manager' => array(
			'doctype' => 'HTML5',
			'template_map' => array(
				'email/layout' => __DIR__ . '/../view/email/layout.phtml',
				'email/header' => __DIR__ . '/../view/email/header.phtml',
				'email/footer' => __DIR__ . '/../view/email/footer.phtml',
				'email/default' => __DIR__ . '/../view/email/default.phtml',
			),
			'template_path_stack' => array(__DIR__ . '/../view')
		),
		'transporters' => array(
			\Messenger\Service\MessengerService::MEDIA_EMAIL => 'EmailTransporter'
		)
	),
	'asset_bundle' => include 'module.config.assets.php',
	'service_manager' => array(
		'factories' => array(
			//Services
			'MessengerService' => '\Messenger\Service\MessengerServiceFactory',

			//Transporters
			'EmailTransporter' => function(){
				return new \Messenger\Mail\Transport\Sendmail();
			},

			//InlineStyle
			'InlineStyleService' => function(\Zend\ServiceManager\ServiceManager $oServiceManager){
				return new \Messenger\Mail\InlineStyle\InlineStyleService(new \Messenger\Mail\InlineStyle\InlineStyleOptions(array(
					'serverUrl' => $oServiceManager->get('ViewHelperManager')->get('ServerUrl')->__invoke()
				)));
			}
		)
	)
);