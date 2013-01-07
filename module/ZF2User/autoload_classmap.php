<?php
return array(
	//Authentication
	'ZF2User\Authentication\AuthenticationService' => __DIR__.'/src/ZF2User/Authentication/AuthenticationService.php',

	//Controllers
	'ZF2User\Controller\UserController' => __DIR__.'/src/ZF2User/Controller/UserController.php',

	//Entities
	'ZF2User\Entity\UserEntity' => __DIR__.'/src/ZF2User/Entity/UserEntity.php',
	'ZF2User\Entity\UserProviderEntity' => __DIR__.'/src/ZF2User/Entity/UserProviderEntity.php',

	//Factories
	'ZF2User\Factory\AuthAdapterFactory' => __DIR__.'/src/ZF2User/Factory/AuthAdapterFactory.php',
	'ZF2User\Factory\AuthServiceFactory' => __DIR__.'/src/ZF2User/Factory/AuthServiceFactory.php',
	'ZF2User\Factory\AuthStorageFactory' => __DIR__.'/src/ZF2User/Factory/AuthStorageFactory.php',
	'ZF2User\Factory\HybridAuthAdapterFactory' => __DIR__.'/src/ZF2User/Factory/HybridAuthAdapterFactory.php',
	'ZF2User\Factory\LoginFormFactory' => __DIR__.'/src/ZF2User/Factory/LoginFormFactory.php',
	'ZF2User\Factory\RegisterFormFactory' => __DIR__.'/src/ZF2User/Factory/RegisterFormFactory.php',
	'ZF2User\Factory\ResetPasswordFormFactory' => __DIR__.'/src/ZF2User/Factory/ResetPasswordFormFactory.php',
	'ZF2User\Factory\SessionManagerFactory' => __DIR__.'/src/ZF2User/Factory/SessionManagerFactory.php',
	'ZF2User\Factory\UserModelFactory' => __DIR__.'/src/ZF2User/Factory/UserModelFactory.php',
	'ZF2User\Factory\UserProviderModelFactory' => __DIR__.'/src/ZF2User/Factory/UserProviderModelFactory.php',
	'ZF2User\Factory\UserServiceFactory' => __DIR__.'/src/ZF2User/Factory/UserServiceFactory.php',

	//Forms
	'ZF2User\Form\ChangePasswordForm' => __DIR__.'/src/ZF2User/Form/ChangePasswordForm.php',
	'ZF2User\Form\LoginForm' => __DIR__.'/src/ZF2User/Form/LoginForm.php',
	'ZF2User\Form\RegisterForm' => __DIR__.'/src/ZF2User/Form/RegisterForm.php',
	'ZF2User\Form\ResetPasswordForm' => __DIR__.'/src/ZF2User/Form/ResetPasswordForm.php',


	//Models
	'ZF2User\Model\UserModel' => __DIR__.'/src/ZF2User/Model/UserModel.php',
	'ZF2User\Model\UserProviderModel' => __DIR__.'/src/ZF2User/Model/UserProviderModel.php',

	//Controllers Plugins
	'ZF2User\Mvc\Controller\Plugin\UserMustBeLoggedInPlugin' =>  __DIR__.'/src/ZF2User/Mvc/Controller/Plugin/UserMustBeLoggedInPlugin.php',

	//Module
	'ZF2User\Module' => __DIR__.'/Module.php',

	//Services
	'ZF2User\Service\UserService' => __DIR__.'/src/ZF2User/Service/UserService.php',

	//Validators
	'ZF2User\Validator\EmailAddressAvailabilityValidator' => __DIR__.'/src/ZF2User/Validator/EmailAddressAvailabilityValidator.php'

);