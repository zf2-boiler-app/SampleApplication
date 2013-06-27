<?php
define('ENV_PRODUCTION','production');
defined('APPLICATION_ENV') || define('APPLICATION_ENV','dev');
return array(
    'modules' => array(
    	//Db
    	'BoilerAppDb',
    	'DoctrineModule',
    	'DoctrineORMModule',

    	//Logger
    	'BoilerAppLogger',

    	//Access Control
    	'BoilerAppAccessControl',
    	'BoilerAppUser',

    	//Display
    	'TwbBundle',
    	'AssetsBundle',
    	'TreeLayoutStack',
    	'BoilerAppDisplay',

    	//Messenger
    	'BoilerAppMessenger',

    	//Sample Application Modules
    	'Application'
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array( 'config/autoload/{,*.}{global,local,private}.php'),
        'module_paths' => array('./module','./vendor'),
    	'cache_dir' => 'data/config-cache/',
    	'config_cache_enabled' => APPLICATION_ENV === ENV_PRODUCTION,
    	'module_map_cache_enabled' =>  APPLICATION_ENV === ENV_PRODUCTION
    )
);