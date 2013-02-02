<?php
return array(
	'router' => array(
		'routes' => array(
			'blog' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/blog',
					'defaults' => array(
						'controller' => 'Blog\Controller\Index',
						'action' => 'index'
					)
				),
				'may_terminate' => true,
				'child_routes' => array(
					'news' => array(
						'type' => 'Zend\Mvc\Router\Http\Literal',
						'options' => array(
							'route' => '/news',
							'defaults' => array(
								'controller' => 'Blog\Controller\News',
								'action' => 'index'
							)
						),
						'may_terminate' => true,
						'child_routes' => array(
							'create' => array(
								'type' => 'Zend\Mvc\Router\Http\Literal',
								'options' => array(
									'route' => '/create',
									'defaults' => array(
										'controller' => 'Blog\Controller\News',
										'action' => 'create'
									)
								)
							),
							'read' => array(
								'type' => 'Zend\Mvc\Router\Http\Segment',
								'options' => array(
									'route' => '/news',
									'defaults' => array(
										'controller' => 'Blog\Controller\News',
										'action' => 'read/[news_id]'
									)
								)
							),
							'update' => array(
								'type' => 'Zend\Mvc\Router\Http\Segment',
								'options' => array(
									'route' => '/news',
									'defaults' => array(
										'controller' => 'Blog\Controller\News',
										'action' => 'read/[news_id]'
									)
								)
							),
							'delete' => array(
								'type' => 'Zend\Mvc\Router\Http\Segment',
								'options' => array(
									'route' => '/news',
									'defaults' => array(
										'controller' => 'Blog\Controller\News',
										'action' => 'delete/[news_id]'
									)
								)
							)
						)
					)
				)
			)
		)
	),
	'controllers' => array(
		'invokables' => array(
			'Blog\Controller\Index' => 'Blog\Controller\IndexController',
			'Blog\Controller\News' => 'Blog\Controller\NewsController'
		),
	),
	'translator' => array(
		'translation_file_patterns' => array(
			array(
				'type' => 'phparray',
				'base_dir' => __DIR__ . '/../languages',
				'pattern'  => '%s/Common.php'
			),
			array(
				'type' => 'phparray',
				'base_dir' => __DIR__ . '/../languages',
				'pattern'  => '%s/Validate.php',
        		'text_domain' => 'validator'
			)
		)
	),
	'templating' => array(
		'template_map' => array(
			'Blog' => array(
				'template' => 'layout/layout',
				'children' => array(
					'specialLayout' => array(
						'template' => 'layout/blog',
						'children' => array(
							'userSection' => function(\Zend\Mvc\MvcEvent $oEvent){
								try{
									if($oEvent->getApplication()->getServiceManager()->get('UserAuthenticationService')->hasIdentity()){
		    							$oEvent->getViewModel()->loggedUser = $oEvent->getApplication()->getServiceManager()->get('UserService')->getLoggedUser();
		    							return 'header/blog-logged';
	    							}
								}
								catch(\Exception $oException){}
	    						return 'header/blog-unlogged';
							},
							'navbar' => function(\Zend\Mvc\MvcEvent $oEvent){
								try{
									if($oEvent->getApplication()->getServiceManager()->get('UserAuthenticationService')->hasIdentity()){
										$oEvent->getViewModel()->loggedUser = $oEvent->getApplication()->getServiceManager()->get('UserService')->getLoggedUser();
										return 'navbar/blog-logged';
									}
								}
								catch(\Exception $oException){}
								return 'navbar/blog-unlogged';
							},
							'footer' => 'footer/footer'
						)
					)
				)
			)
		)
	),
	'view_manager' => array(
		'template_map' => array(
			'layout/blog' => __DIR__ . '/../view/layout/blog.phtml',
			'header/blog-logged' => __DIR__ . '/../view/blog/header/blog-logged.phtml',
			'header/blog-unlogged' => __DIR__ . '/../view/blog/header/blog-unlogged.phtml',
			'navbar/blog-logged' => __DIR__ . '/../view/blog/navbar/blog-logged.phtml',
			'navbar/blog-unlogged' => __DIR__ . '/../view/blog/navbar/blog-unlogged.phtml'
		),
		'template_path_stack' => array('Blog' => __DIR__ . '/../view'),
	)
);