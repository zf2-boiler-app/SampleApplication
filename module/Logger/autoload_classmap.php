<?php
return array(
	//Adapters
	'Logger\Service\Adapter\DbLogAdapter' => __DIR__.'/src/Logger/Service/Adapter/DbLogAdapter.php',
	'Logger\Service\Adapter\LogAdapterInterface' => __DIR__.'/src/Logger/Service/Adapter/LogAdapterInterface.php',

	//Factories
	'Logger\Service\LoggerServiceFactory' => __DIR__.'/src/Logger/Service/LoggerServiceFactory.php',
		
	//Services
	'Logger\Service\LoggerService' => __DIR__.'/src/Logger/Service/LoggerService.php'
);