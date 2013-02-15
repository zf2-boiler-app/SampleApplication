<?php
/**
 * Local Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 *
 * @NOTE: This file is ignored from Git by default with the .gitignore included
 * in ZendSkeletonApplication. This is a good practice, as it prevents sensitive
 * credentials from accidentally being committed into version control.
 */
return array(
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
		'dsn' => 'mysql:dbname=zf2base;host=localhost'
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
			'name' => 'ZF2Base'
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