<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Blog\Controller\News' => 'Blog\Controller\NewsController'
        )
    ),
    'router' => array(
        'routes' => array()
    ),
	'asset_bundle' => array(
    	'assets' => array(
    		'zf2user' => array(
    			'ZF2User\Controller\User' => array(
    				'js' => array(
	    				'js/Blog/Controller/NewsController.js'
	    			)
    			)
    		)
    	)
	),
	'view_manager' => array(
    	'template_path_stack' => array('Blog' => __DIR__ . '/../view')
    ),
	'service_manager' => array(
		'factories' => array(
			'NewsModel' => '\ZF2User\Factory\NewsModelFactory'
		)
	)
);