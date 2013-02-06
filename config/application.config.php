<?php
return array(
    'modules' => array(
    	'ZFTool',
        'Application',
    	'Database',
    	'Templating',
    	'AssetsBundle',
    	'TwbBundle',
    	'User',
    	'Logger',
    	'Messenger',
    	'Blog',
    	'CKEditorBundle'
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array( 'config/autoload/{,*.}{global,local,private}.php'),
        'module_paths' => array('./module','./vendor')
    )
);