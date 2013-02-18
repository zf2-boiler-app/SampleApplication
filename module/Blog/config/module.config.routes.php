<?php
return array(
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
				'post' => array(
					'type' => 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
						'route' => '/post',
						'defaults' => array(
							'controller' => 'Blog\Controller\Post',
							'action' => 'index'
						)
					),
					'may_terminate' => true,
					'child_routes' => array(
						'all' => array(
							'type' => 'Zend\Mvc\Router\Http\Segment',
							'options' => array(
								'route' => '/all/:page',
								'defaults' => array(
									'controller' => 'Blog\Controller\Post',
									'action' => 'index',
									'page' => 1,
								)
							)
						),
						'create' => array(
							'type' => 'Zend\Mvc\Router\Http\Literal',
							'options' => array(
								'route' => '/create',
								'defaults' => array(
									'controller' => 'Blog\Controller\Post',
									'action' => 'create'
								)
							)
						),
						'read' => array(
							'type' => 'Zend\Mvc\Router\Http\Segment',
							'options' => array(
								'route' => '/read/:post_id[/:post_title]',
								'defaults' => array(
									'controller' => 'Blog\Controller\Post',
									'action' => 'read'
								)
							)
						),
						'update' => array(
							'type' => 'Zend\Mvc\Router\Http\Segment',
							'options' => array(
								'route' => '/update/:post_id',
								'defaults' => array(
									'controller' => 'Blog\Controller\Post',
									'action' => 'update'
								)
							)
						),
						'delete' => array(
							'type' => 'Zend\Mvc\Router\Http\Segment',
							'options' => array(
								'route' => '/delete/:post_id',
								'defaults' => array(
									'controller' => 'Blog\Controller\Post',
									'action' => 'delete'
								)
							)
						)
					)
				)
			)
		)
	)
);