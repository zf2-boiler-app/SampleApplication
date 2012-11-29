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
    	\Application\View\Helper\Social::GOOGLE => array(
    		'id' => 'google_id',
    		'key' => 'google_key'
    	),
    	\Application\View\Helper\Social::TWITTER => array(
    		'id' => 'twitter_id',
    		'key' => 'twitter_key'
    	),
    	\Application\View\Helper\Social::FACEBOOK => array(
    		'id' => 'facebook_id',
    		'key' => 'facebook_key'
    	),
    	\Application\View\Helper\Social::GOOGLE_ANALYTICS => array(
    		'id' => 'analytics_id'
    	)
    ),
	'db' => array(
		'username' => 'root',
		'password' => '',
		'dsn' => 'mysql:dbname=zf2base;host=localhost'
	)
);
