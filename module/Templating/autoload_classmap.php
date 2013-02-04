<?php
return array(
	//Factories
	'Templating\Factory\TemplatingServiceFactory' => __DIR__.'/src/Templating/Factory/TemplatingServiceFactory.php',

	//MVC
	'Templating\Mvc\Controller\AbstractActionController' => __DIR__.'/src/Templating/Mvc/Controller/AbstractActionController.php',

	//Services
	'Templating\Service\TemplatingService' => __DIR__.'/src/Templating/Service/TemplatingService.php',
	'Templating\Service\TemplatingConfiguration' => __DIR__.'/src/Templating/Service/TemplatingConfiguration.php',
	'Templating\Service\Template\Template' => __DIR__.'/src/Templating/Service/Template/Template.php',
	'Templating\Service\Template\TemplateConfiguration' => __DIR__.'/src/Templating/Service/Template/TemplateConfiguration.php'
);