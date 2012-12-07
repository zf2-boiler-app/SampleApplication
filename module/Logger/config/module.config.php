<?php
return array(
	'logger' => array(
		'adapters' => 'LogAdapter'
	),
	'service_manager' => array(
		'factories' => array(
			//Log adapters
			'LogAdapter' =>  function(\Zend\ServiceManager\ServiceManager $oServiceManager){
            	$oLogAdapter = new \Logger\Service\Adapter\DbLogAdapter('logs',$oServiceManager->get('Zend\Db\Adapter\Adapter'));
            	if($oServiceManager->has('AuthService'))$oLogAdapter->setAuthService($oServiceManager->get('AuthService'));
            	return $oLogAdapter;
            },
			//Services
			'LoggerService' => '\Logger\Service\LoggerServiceFactory'
		)
	),
);