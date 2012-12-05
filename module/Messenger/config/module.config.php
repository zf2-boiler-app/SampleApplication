<?php
return array(
    'service_manager' => array(
    	'factories' => array(
    		//Managers
    		'mail' => function(\Zend\ServiceManager\ServiceManager $oServiceManager){
    			$aConfig = $oServiceManager->get('config');
    			if(!isset($aConfig['mailer']))throw new \Exception('Mailer config is undefined');
    			return new \Medias\Managers\Mail($aConfig['mailer']);
    		},
    	)
    ),
    'mailer' => array(
    	'sender' => array(
    		'DEFAULT' => 'default@void.com',
    		'NOREPLY' => 'noreply@void.com',
    		'SYSTEME' => 'systeme@void.com'
    	),
    	'view_manager' => array(
	        'doctype' => 'HTML5',
	        'template_map' => array(
	    		'mail/default' => __DIR__ . '/../view/mail/default.phtml',
	        	'mail/exception' => __DIR__ . '/../view/mail/exception.phtml',
	            'mail/layout' => __DIR__ . '/../view/mail/layout.phtml',
	        	'mail/footer' => __DIR__ . '/../view/mail/footer.phtml',
	        	'mail/header' => __DIR__ . '/../view/mail/header.phtml',
	        ),
	        'template_path_stack' => array(__DIR__ . '/../view')
    	)
    )
);