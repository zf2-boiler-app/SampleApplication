<?php
return array(
	'router' => include 'module.config.routes.php',
	'translator' => array(
		'locale' => 'fr_FR',
		'translation_file_patterns' => array(
			array(
				'type' => 'phparray',
				'base_dir' => __DIR__ . '/../languages',
				'pattern'  => '%s/Common.php'
			)
		),
		//Zend translations
		'translation_files' => array(
			array(
				'type' => 'phparray',
				'filename' =>  getcwd().'/vendor/zendframework/zendframework/resources/languages/fr/Zend_Validate.php',
				'locale'  => 'fr_FR',
				'text_domain' => 'validator'
			),
			array(
				'type' => 'phparray',
				'filename' =>  getcwd().'/vendor/zendframework/zendframework/resources/languages/fr/Zend_Captcha.php',
				'locale'  => 'fr_FR',
				'text_domain' => 'validator'
			),
			array(
				'type' => 'phparray',
				'filename' =>  getcwd().'/vendor/zendframework/zendframework/resources/languages/en/Zend_Validate.php',
				'locale'  => 'en_US',
				'text_domain' => 'validator'
			),
			array(
				'type' => 'phparray',
				'filename' =>  getcwd().'/vendor/zendframework/zendframework/resources/languages/en/Zend_Captcha.php',
				'locale'  => 'en_US',
				'text_domain' => 'validator'
			)
		)
	),
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
			'Session' =>  function(){
				return new \Zend\Session\Container('zf2app');
			}
		)
	),
	'view_manager' => array(
		'template_map' => array(
			'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
			'layout/default' => __DIR__ . '/../view/layout/default.phtml',
			'header/logged' => __DIR__ . '/../view/application/header/logged.phtml',
			'header/unlogged' => __DIR__ . '/../view/application/header/unlogged.phtml',
			'footer/footer' => __DIR__ . '/../view/application/footer/footer.phtml'
		)
	)
);