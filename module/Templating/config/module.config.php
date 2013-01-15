<?php
return array(
	'templating' => array(
		'template_map' => array(
			'default' => array(
				'layout' => 'layout/default',
				'children' => array(
					'header' => 'header/default',
					'footer' => 'footer/default'
				)
			),
			'Blog' => array(
				'layout' => 'layout/blog',
				'children' => array(
					'header' => 'header/blog',
					'footer' => 'footer/default'
				)
			)
		)
	),
	'service_manager' => array(
		'factories' => array(
			//Services
			'TemplatingService' => '\Templating\Factory\TemplatingServiceFactory'
		)
	)
);