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
								'route' => '/news',
								'defaults' => array(
									'controller' => 'Blog\Controller\Post',
									'action' => 'read/[post_id]'
								)
							)
						),
						'update' => array(
							'type' => 'Zend\Mvc\Router\Http\Segment',
							'options' => array(
								'route' => '/news',
								'defaults' => array(
									'controller' => 'Blog\Controller\Post',
									'action' => 'read/[post_id]'
								)
							)
						),
						'delete' => array(
							'type' => 'Zend\Mvc\Router\Http\Segment',
							'options' => array(
								'route' => '/news',
								'defaults' => array(
									'controller' => 'Blog\Controller\Post',
									'action' => 'delete/[post_id]'
								)
							)
						)
					)
				)
			)
		)
	)
);