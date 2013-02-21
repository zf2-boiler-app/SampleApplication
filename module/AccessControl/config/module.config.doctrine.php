<?php
return array(
	'driver' => array(
		'accesscontrol_driver' => array(
			'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
			'cache' => 'array',
			'paths' => array(__DIR__ . '/../src/AccessControl/Entity')
		),
		'orm_default' => array(
			'drivers' => array(
				'AccessControl\Entity' => 'accesscontrol_driver'
			)
		)
	),
	'configuration' => array(
		'orm_default' => array(
			'types' => array(
				'authaccessstateenum' => 'AccessControl\Doctrine\DBAL\Types\AuthAccessStateEnumType'
			)
		)
	)
);