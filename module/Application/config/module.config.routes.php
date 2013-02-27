<?php
return array(
	'routes' => array(
		'Home' => array(
			'type' => 'Zend\Mvc\Router\Http\Literal',
			'options' => array(
				'route' => '/',
				'defaults' => array(
					'controller' => 'Application\Controller\Index',
					'action' => 'index'
				)
			)
		),
		'Terms' => array(
			'type' => 'Zend\Mvc\Router\Http\Literal',
			'options' => array(
				'route' => '/terms',
				'defaults' => array(
					'controller' => 'Application\Controller\Index',
					'action' => 'terms'
				)
			)
		),
		'Privacy' => array(
			'type' => 'Zend\Mvc\Router\Http\Literal',
			'options' => array(
				'route' => '/privacy',
				'defaults' => array(
					'controller' => 'Application\Controller\Index',
					'action' => 'privacy'
				)
			)
		)
	)
);