<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return array(
	'translator' => array(
		'locale' => 'fr_FR'
	),
	'asset_bundle' => array(
		'production' => APPLICATION_ENV === ENV_PRODUCTION,
		'cachePath' => '@zfRootPath/public/assets/cache',
		'assetsPath' => '@zfRootPath/public/assets',
		'cacheUrl' => '@zfBaseUrl/assets/cache/'
	),
	'db' => array(
		'username' => 'root',
		'password' => '',
		'dsn' => 'mysql:dbname=sample-app;host=localhost'
	),
	'doctrine' => array(
		'connection' => array(
			'orm_default' => array(
				'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
				'params' => array(
					'host' => 'localhost',
					'user' => 'root',
					'password' => '',
					'dbname' => 'sample-application'
				)
			)
		)
	),
	'messenger' => array(
		'system_user' => array(
			'email' => 'system@sample-application.com',
			'display_name' => 'Sample Application'
		)
	),
	'style_inliner' => array(
		'processor' => 'CssToInlineStylesProcessor'
	)
);