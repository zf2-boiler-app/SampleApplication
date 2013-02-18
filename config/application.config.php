<?php
return array(
    'modules' => array(
    	'ZFTool',
    	'DoctrineModule',
    	'DoctrineORMModule',
    	'TwbBundle',
    	'AssetsBundle',
    	'CKEditorBundle',
        'Application',
    	'Database',
    	'Templating',
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