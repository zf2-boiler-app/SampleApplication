<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
	'db' => array(
		'driver' => 'Pdo',
		'driver_options' => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')
	),
	'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'asset_bundle' => array(
    	'assets' => array(
    		'application' => array(
    			'less' => array(
    				'@zfRootPath/vendor/fortawesome/font-awesome/less/font-awesome.less',
    				'@zfRootPath/vendor/twitter/bootstrap/less/bootstrap.less',
    			),
    			'js' => array(
    				/*'js/mootools/mootools-core-1.4.5.js',
    				'js/mootools',
    				'js/modernizr.min.js'*/
    			),
    			'media' => array(
    				//'@zfRootPath/vendor/twitter/bootstrap/img',
    				'@zfRootPath/vendor/fortawesome/font-awesome/font'
    			)
    		)
    	)
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory'
        ),
    ),
    'translator' => array(
        'locale' => 'fr_FR',
        //'cache' => array('adapter'=> 'Zend\Cache\Storage\Adapter\Memcached'),
        'translation_file_patterns' => array(
            array(
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.php'
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
        		'filename' =>  getcwd().'/vendor/zendframework/zendframework/resources/languages/en/Zend_Validate.php',
        		'locale'  => 'en_US',
        		'text_domain' => 'validator'
	        )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'factories' => array(
            'social' => function(\Zend\ServiceManager\ServiceManager $oServiceManager){
    			$aConfiguration = $oServiceManager->getServiceLocator()->get('config');
				if(!isset($aConfiguration['social']))throw new \Exception('Social configuration is undefined');
            	return new \Application\View\Helper\Social($aConfiguration['social']);
    		}
        )
    )
);
