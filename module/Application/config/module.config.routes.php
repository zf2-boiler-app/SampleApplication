<?php
return array(
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
);