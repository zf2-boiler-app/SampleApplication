<?php
return array(
	//Emogrifier don't have autoloader
	'Emogrifier' =>  __DIR__ . '/vendor/Emogrifier/emogrifier.php',
		
	//Emogrifier don't have autoloader
	'InlineStyle' =>  __DIR__ . '/../../vendor/inlinestyle/inlinestyle/InlineStyle/InlineStyle.php',
		
	//Factories
	'Messenger\Service\MessengerServiceFactory' => __DIR__.'/src/Messenger/Service/MessengerServiceFactory.php',
	
	//InlineStyle
	'Messenger\Mail\InlineStyle\InlineStyleOptions' => __DIR__.'/src/Messenger/Mail/InlineStyle/InlineStyleOptions.php',
	'Messenger\Mail\InlineStyle\InlineStyleService' => __DIR__.'/src/Messenger/Mail/InlineStyle/InlineStyleService.php',	
	
	//Mail
	'Messenger\Mail\Message' => __DIR__.'/src/Messenger/Mail/Message.php',
		
	//Renderer
	'Messenger\View\Renderer\EmailRenderer' => __DIR__.'/src/Messenger/View/Renderer/EmailRenderer.php',
		
	//Transporters
	'Messenger\Mail\Transport\Sendmail' => __DIR__.'/src/Messenger/Mail/Transport/Sendmail.php',
	'Messenger\Mail\Transport\Smtp' => __DIR__.'/src/Messenger/Mail/Transport/Smtp.php',
		
	//Services
	'Messenger\Message' => __DIR__.'/src/Messenger/Message.php',
	'Messenger\Service\MessengerService' => __DIR__.'/src/Messenger/Service/MessengerService.php'
);