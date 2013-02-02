<?php
return array(
	'router' => array(
		'routes' => array(
			'home' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route'    => '/',
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action'     => 'index'
					)
				)
			),
			'terms' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route'    => '/terms',
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action' => 'terms'
					)
				)
			),
			'privacy' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route'    => '/privacy',
					'defaults' => array(
						'controller' => 'Application\Controller\Index',
						'action' => 'privacy'
					)
				)
			)
		)
	),
	'asset_bundle' => array(
		'assets' => array(
			'less' => array(
				'less/global.less',
				'less/bootstrap-custom.less',
				'@zfRootPath/vendor/fortawesome/font-awesome/less/font-awesome.less',
				'@zfRootPath/vendor/twitter/bootstrap/less/bootstrap.less'
			),
			'js' => array(
				'js/mootools/mootools-core-1.4.5.js',
				'js/mootools/mootools-more-1.4.0.1.js',
				'js/mootools/mootools-bootstrap.js',
				'js/mootools',
				'js/modernizr.min.js',
				'@zfRootPath/vendor/fabiomcosta/mootools-meio-mask/Source/Meio.Mask.js',
				'@zfRootPath/vendor/fabiomcosta/mootools-meio-mask/Source',
				'js/MeioMask/behavior.js',
				'@zfRootPath/vendor/arian/iFrameFormRequest/Source/iFrameFormRequest.js',
				'js/controller.js',
				'js/Modal',
				'js/Validator/common.js'
			),
			'media' => array(
				'@zfRootPath/vendor/fortawesome/font-awesome/font',
				'images',
				'@zfRootPath/vendor/twitter/bootstrap/img'
			)
		)
	),
	'service_manager' => array(
		'factories' => array(
			'translator' => 'Application\Translator\TranslatorServiceFactory',
			'social' => function(\Zend\ServiceManager\ServiceManager $oServiceManager){
				$aConfiguration = $oServiceManager->get('config');
				if(!isset($aConfiguration['social']))throw new \Exception('Social configuration is undefined');
				return new \Application\View\Helper\SocialHelper($aConfiguration['social']);
			},
			'Session' =>  function(){
				return new \Zend\Session\Container('zf2app');
			},
			'FormHelper' => function(\Zend\ServiceManager\ServiceManager $oServiceManager){
				return new \Application\Form\View\Helper\FormHelper(
					new \DluTwBootstrap\GenUtil(),
					new \DluTwBootstrap\Form\FormUtil(),
					$oServiceManager->get('Request')
				);
			}
		)
	),
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
								try{
									if($oEvent->getApplication()->getServiceManager()->get('UserAuthenticationService')->hasIdentity()){
		    							$oEvent->getViewModel()->loggedUser = $oEvent->getApplication()->getServiceManager()->get('UserService')->getLoggedUser();
		    							return 'header/logged';
	    							}
								}
								catch(\Exception $oException){}
	    						return 'header/unlogged';
							},
							'footer' => 'footer/footer'
						)
					)
				)
			)
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
			},
			'form' => function(\Zend\ServiceManager\ServiceManager $oServiceManager){
				return $oServiceManager->getServiceLocator()->get('FormHelper');
			}
		),
		'invokables' => array(
			'escapeJson' => 'Application\View\Helper\EscapeJsonHelper',
			'jsController' => 'Application\View\Helper\JsControllerHelper',
		)
	)
);