<?php
return array(
	'router' => include 'module.config.routes.php',
	'asset_bundle' => include 'module.config.assets.php',
	'translator' => array(
		'locale' => 'fr_FR',
		//'cache' => array('adapter'=> 'Zend\Cache\Storage\Adapter\Memcached'),
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
			'SessionManager' => 'Application\Factory\SessionManagerFactory',
			'translator' => 'Application\Translator\TranslatorServiceFactory',
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
		'display_not_found_reason' => true,
		'display_exceptions' => true,
		'doctype' => 'HTML5',
		'not_found_template' => 'error/404',
		'exception_template' => 'error/index',
		'template_map' => array(
			'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
			'layout/default' => __DIR__ . '/../view/layout/default.phtml',
			'header/logged' => __DIR__ . '/../view/application/header/logged.phtml',
			'header/unlogged' => __DIR__ . '/../view/application/header/unlogged.phtml',
			'footer/footer' => __DIR__ . '/../view/application/footer/footer.phtml',
			'error/404' => __DIR__ . '/../view/error/404.phtml',
			'error/index' => __DIR__ . '/../view/error/index.phtml'
		),
		'template_path_stack' => array(__DIR__ . '/../view'),
		'strategies' => array('ViewJsonStrategy')
	),
	'view_helpers' => array(
		'factories' => array(
			'social' => function(\Zend\ServiceManager\ServiceManager $oServiceManager){
				return $oServiceManager->getServiceLocator()->get('social');
			}
		),
		'invokables' => array(
			'form' => 'Application\Form\View\Helper\FormHelper',
			'formElementErrors' => 'Application\Form\View\Helper\FormElementErrors',
			'escapeJson' => 'Application\View\Helper\EscapeJsonHelper',
			'jsController' => 'Application\View\Helper\JsControllerHelper',
		)
	)
);