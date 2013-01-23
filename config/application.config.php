<?php
return array(
    'modules' => array(
    	'ZFTool',
        'Application',
    	'Database',
    	'Templating',
    	'Neilime\AssetsBundle',
    	'DluTwBootstrap',
    	'User',
    	'Logger',
    	'Messenger',
    	'Blog'
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array( 'config/autoload/{,*.}{global,local,private}.php'),
        'module_paths' => array('./module','./vendor')
    )
);