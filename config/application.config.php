<?php
return array(
    'modules' => array(
        'Application',
    	'Neilime\AssetsBundle',
    	'DluTwBootstrap',
    	'ZF2User',
    	'Logger'
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array( 'config/autoload/{,*.}{global,local,private}.php'),
        'module_paths' => array('./module','./vendor')
    )
);