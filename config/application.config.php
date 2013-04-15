<?php
return array(
    'modules' => array(
    	//Display
    	'BoilerAppDisplay',
    	'AssetsBundle',
    	'TreeLayoutStack',

    	//Access Control
    	'BoilerAppAccessControl',

    	//Sample Application Modules
    	'Application'
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array( 'config/autoload/{,*.}{global,local,private}.php'),
        'module_paths' => array('./module','./vendor')
    )
);