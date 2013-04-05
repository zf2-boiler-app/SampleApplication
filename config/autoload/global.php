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
		'production' => false,
		'cachePath' => '@zfRootPath/public/assets/cache',
		'assetsPath' => '@zfRootPath/public/assets',
		'cacheUrl' => '@zfBaseUrl/assets/cache/'
	),
	'social' => array(
		//https://code.google.com/apis/console/
		\Application\View\Helper\SocialHelper::GOOGLE => array(
			'id' => 'google_id',
			'key' => 'google_key'
		),
		\Application\View\Helper\SocialHelper::TWITTER => array(
			'id' => 'twitter_id',
			'key' => 'twitter_key'
		),
		//https://developers.facebook.com/apps
		\Application\View\Helper\SocialHelper::FACEBOOK => array(
			'id' => 'facebook_id',
			'key' => 'facebook_key'
		),
		\Application\View\Helper\SocialHelper::GOOGLE_ANALYTICS => array(
			'id' => 'analytics_id'
		)
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
					'host'     => 'localhost',
					'user'     => 'root',
					'password' => '',
					'dbname'   => 'zf2base'
				)
			)
		)
	),
	'messenger' => array(
		'system_user' => array(
			'email' => 'email@mail.com',
			'name' => 'Sample-App'
		)
	),
	'service_manager' => array(
		'factories' => array(
			//Transporters
			'EmailTransporter' => function(){
				//Send email with Gmail SMTP (need openssl php extension)
				$oTransporter = new \Messenger\Mail\Transport\Smtp(new \Zend\Mail\Transport\SmtpOptions(array(
					'host' => 'smtp.gmail.com',
					'connection_class'  => 'plain',
					'connection_config' => array(
						'ssl' => 'tls',
						'username' => 'email@gmail.com',
						'password' => 'password'
					)
				)));
				return $oTransporter;
			}
		)
	)
);