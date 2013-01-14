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
						'action' => 'index',
					),
				),
				'may_terminate' => true
			)
		)
	),
	'controllers' => array(
		'invokables' => array(
			'Blog\Controller\Index' => 'Blog\Controller\IndexController'
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
	'view_manager' => array(
		'template_map' => array(
			'layout/blog' => __DIR__ . '/../view/layout/blog.phtml'
		),
		'template_path_stack' => array('Blog' => __DIR__ . '/../view'),
    	'specialLayout' => array(
    		'blog' => 'layout/blog'
    	)
	)
);