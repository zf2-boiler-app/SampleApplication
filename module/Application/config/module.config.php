<?php
return array(
	'router' => include 'module.config.routes.php',
	'translator' => include 'module.config.translations.php',
	'controllers' => array(
		'invokables' => array(
			'Application\Controller\Index' => 'Application\Controller\IndexController'
		)
	),
	'tree_layout_stack' => array(
		'layout_tree' => array(
			'default' => array(
				'template' => 'layout/layout',
				'children' => array(
					'specialLayout' => array(
						'template' => 'layout/default',
						'children' => array(
							'header' => function(\Zend\Mvc\MvcEvent $oEvent){
								return $oEvent->getViewModel()->authenticatedUser?'header/logged':'header/unlogged';
							},
							'footer' => 'footer/footer'
						)
					)
				)
			)
		)
	),
	'service_manager' => array(
		'factories' => array(
			'Logger' => function(){
				if(defined('STDERR'))$sStream = STDERR;
				elseif(!($sStream = ini_get('error_log')))throw new \LogicException('Unable to defined logger output stream');
				return new \Zend\Log\Logger(array('writers' => array(
					array('name' => 'Zend\Log\Writer\Stream','options' => array('stream' => $sStream))
				)));
			}
		)
	),
	'view_manager' => array(
		'template_map' => array(
			'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
			'layout/default' => __DIR__ . '/../view/layout/default.phtml',
			'header/logged' => __DIR__ . '/../view/application/header/logged.phtml',
			'header/unlogged' => __DIR__ . '/../view/application/header/unlogged.phtml',
			'footer/footer' => __DIR__ . '/../view/application/footer/footer.phtml',
			'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
			'application/index/terms' => __DIR__ . '/../view/application/index/terms.phtml',
			'application/index/privacy' => __DIR__ . '/../view/application/index/privacy.phtml'
		)
	),
	'medias' => array(
		\BoilerAppMessenger\Media\Mail\MailMessageRenderer::MEDIA => array(
			'template_map' => array(
				'mail/layout' => __DIR__.'/../view/mail/layout.phtml',
				'mail/header' => __DIR__.'/../view/mail/header.phtml',
				'mail/footer' => __DIR__.'/../view/mail/footer.phtml'
			),
			'tree_layout_stack' => array(
				'layout_tree' => array(
					'default' => array(
						'template' => 'mail/layout',
						'children' => array(
							'header' => 'mail/header',
							'footer' => 'mail/footer'
						)
					)
				)
			)
		)
	),
	'authentication' => array(
		'defaultRedirect' => 'Home'
	)
);