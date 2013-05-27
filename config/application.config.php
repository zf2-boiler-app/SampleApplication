<?php
return array(
    'modules' => array(
    	//Db
    	'BoilerAppDb',
    	'DoctrineModule',
    	'DoctrineORMModule',

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
        'module_paths' => array('./module','./vendor')
    )
);