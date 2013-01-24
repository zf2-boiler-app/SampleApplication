<?php
return array(
	//Authentication
	'User\Authentication\AuthenticationService' => __DIR__.'/src/User/Authentication/AuthenticationService.php',

	//Controllers
	'User\Controller\UserController' => __DIR__.'/src/User/Controller/UserController.php',

	//Entities
	'User\Entity\UserEntity' => __DIR__.'/src/User/Entity/UserEntity.php',
	'User\Entity\UserProviderEntity' => __DIR__.'/src/User/Entity/UserProviderEntity.php',

	//Factories
	'User\Factory\AuthAdapterFactory' => __DIR__.'/src/User/Factory/AuthAdapterFactory.php',
	'User\Factory\AuthServiceFactory' => __DIR__.'/src/User/Factory/AuthServiceFactory.php',
	'User\Factory\AuthStorageFactory' => __DIR__.'/src/User/Factory/AuthStorageFactory.php',
	'User\Factory\HybridAuthAdapterFactory' => __DIR__.'/src/User/Factory/HybridAuthAdapterFactory.php',
	'User\Factory\ChangeAvatarFormFactory' => __DIR__.'/src/User/Factory/ChangeAvatarFormFactory.php',
	'User\Factory\ChangeEmailFormFactory' => __DIR__.'/src/User/Factory/ChangeEmailFormFactory.php',
	'User\Factory\ChangePasswordFormFactory' => __DIR__.'/src/User/Factory/ChangePasswordFormFactory.php',
	'User\Factory\LoginFormFactory' => __DIR__.'/src/User/Factory/LoginFormFactory.php',
	'User\Factory\RegisterFormFactory' => __DIR__.'/src/User/Factory/RegisterFormFactory.php',
	'User\Factory\ResetPasswordFormFactory' => __DIR__.'/src/User/Factory/ResetPasswordFormFactory.php',
	'User\Factory\SessionManagerFactory' => __DIR__.'/src/User/Factory/SessionManagerFactory.php',
	'User\Factory\UserModelFactory' => __DIR__.'/src/User/Factory/UserModelFactory.php',
	'User\Factory\UserProviderModelFactory' => __DIR__.'/src/User/Factory/UserProviderModelFactory.php',
	'User\Factory\UserServiceFactory' => __DIR__.'/src/User/Factory/UserServiceFactory.php',

	//Forms
	'User\Form\ChangeAvatarForm' => __DIR__.'/src/User/Form/ChangeAvatarForm.php',
	'User\Form\ChangeEmailForm' => __DIR__.'/src/User/Form/ChangeEmailForm.php',
	'User\Form\ChangePasswordForm' => __DIR__.'/src/User/Form/ChangePasswordForm.php',
	'User\Form\LoginForm' => __DIR__.'/src/User/Form/LoginForm.php',
	'User\Form\RegisterForm' => __DIR__.'/src/User/Form/RegisterForm.php',
	'User\Form\ResetPasswordForm' => __DIR__.'/src/User/Form/ResetPasswordForm.php',

	//Models
	'User\Model\UserModel' => __DIR__.'/src/User/Model/UserModel.php',
	'User\Model\UserProviderModel' => __DIR__.'/src/User/Model/UserProviderModel.php',

	//Controllers Plugins
	'User\Mvc\Controller\Plugin\UserMustBeLoggedInPlugin' =>  __DIR__.'/src/User/Mvc/Controller/Plugin/UserMustBeLoggedInPlugin.php',

	//Module
	'User\Module' => __DIR__.'/Module.php',

	//Services
	'User\Service\UserService' => __DIR__.'/src/User/Service/UserService.php',

	//Validators
	'User\Validator\EmailAddressAvailabilityValidator' => __DIR__.'/src/User/Validator/EmailAddressAvailabilityValidator.php',
	'User\Validator\UserLoggedPasswordValidator' => __DIR__.'/src/User/Validator/UserLoggedPasswordValidator.php',

	//View helpers
	'User\View\Helper\UserAvatarHelper' => __DIR__.'/src/User/View/Helper/UserAvatarHelper.php'
);