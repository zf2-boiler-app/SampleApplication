<?php
return array(
	//Factories
	'Templating\Service\TemplatingServiceFactory' => __DIR__.'/src/Templating/Factory/TemplatingServiceFactory.php',

	//Services
	'Templating\Service\TemplatingService' => __DIR__.'/src/Templating/Service/TemplatingService.php',
	'Templating\Service\TemplatingConfiguration' => __DIR__.'/src/Templating/Service/TemplatingConfiguration.php',
	'Templating\Service\TemplatingService\Template\Template' => __DIR__.'/src/Templating/Service/Template/Template.php',
	'Templating\Service\TemplatingService\Template\TemplateConfiguration' => __DIR__.'/src/Templating/Service/TemplateConfiguration.php'
);