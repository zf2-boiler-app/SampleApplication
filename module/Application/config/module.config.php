<?php
return array(
	'router' => include 'module.config.routes.php',
	'translator' => include 'module.config.translations.php',
	'controllers' => array(
		'invokables' => array(
			'Application\Controller\Index' => 'Application\Controller\IndexController'
		)
	),
	'templating' => array(
		'template_map' => array(
			'default' => array(
				'template' => 'layout/layout',
				'children' => array(
					'specialLayout' => array(
						'template' => 'layout/default',
						'children' => array(
							'header' => function(\Zend\Mvc\MvcEvent $oEvent){
								return $oEvent->getViewModel()->loggedUser?'header/logged':'header/unlogged';
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
			'social' => function(\Zend\ServiceManager\ServiceManager $oServiceManager){
				$aConfiguration = $oServiceManager->get('config');
				if(!isset($aConfiguration['social']))throw new \Exception('Social configuration is undefined');
				return new \Application\View\Helper\SocialHelper($aConfiguration['social']);
			},
			'Logger' => function(){
				$oLogger = new \Zend\Log\Logger();
				return $oLogger->addWriter(new Zend\Log\Writer\FirePHP());
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
			'application/index/index' => __DIR__ . '/../view/application/index/index.phtml'
		)
	),
	'messenger' => array(
		'template_map' => array(
			'email/layout' => __DIR__.'/../view/email/layout.phtml',
			'email/header' => __DIR__.'/../view/email/header.phtml',
			'email/footer' => __DIR__.'/../view/email/footer.phtml',
			'email/default' => __DIR__.'/../view/email/default.phtml'
		),
		'template_path_stack' => array(__DIR__.'/../view'),
		'templating' => array(
			'template_map' => array(
				'default' => array(
					'template' => 'email/layout',
					'children' => array(
						'header' => 'email/header',
						'footer' => 'email/footer'
					)
				)
			)
		),
	)
);