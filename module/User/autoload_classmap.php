<?php
return array(
	//Authentication
	'User\Authentication\UserAuthenticationService' => __DIR__.'/src/User/Authentication/UserAuthenticationService.php',
	'User\Authentication\Adapter\AuthenticationAdapterInterface' => __DIR__.'/src/User/Authentication/Adapter/AuthenticationAdapterInterface.php',
	'User\Authentication\Adapter\AuthenticationDbTableAdapter' => __DIR__.'/src/User/Authentication/Adapter/AuthenticationDbTableAdapter.php',
	'User\Authentication\Adapter\AuthenticationHybridAuthAdapter' => __DIR__.'/src/User/Authentication/Adapter/AuthenticationHybridAuthAdapter.php',

	//Controllers
	'User\Controller\UserController' => __DIR__.'/src/User/Controller/UserController.php',
	'User\Controller\UserAccountController' => __DIR__.'/src/User/Controller/UserAccountController.php',

	//Entities
	'User\Entity\UserEntity' => __DIR__.'/src/User/Entity/UserEntity.php',
	'User\Entity\UserProviderEntity' => __DIR__.'/src/User/Entity/UserProviderEntity.php',

	//Factories
	'User\Factory\AuthenticationDbTableAdapterFactory' => __DIR__.'/src/User/Factory/AuthenticationDbTableAdapterFactory.php',
	'User\Factory\AuthenticationHybridAuthAdapterFactory' => __DIR__.'/src/User/Factory/AuthenticationHybridAuthAdapterFactory.php',
	'User\Factory\UserAuthenticationServiceFactory' => __DIR__.'/src/User/Factory/UserAuthenticationServiceFactory.php',
	'User\Factory\UserAuthenticationStorageFactory' => __DIR__.'/src/User/Factory/UserAuthenticationStorageFactory.php',
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
	'User\Factory\UserAccountServiceFactory' => __DIR__.'/src/User/Factory/UserAccountServiceFactory.php',

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
	'User\Service\UserAccountService' => __DIR__.'/src/User/Service/UserAccountService.php',

	//Validators
	'User\Validator\EmailAddressAvailabilityValidator' => __DIR__.'/src/User/Validator/EmailAddressAvailabilityValidator.php',
	'User\Validator\UserLoggedPasswordValidator' => __DIR__.'/src/User/Validator/UserLoggedPasswordValidator.php',

	//View helpers
	'User\View\Helper\UserAvatarHelper' => __DIR__.'/src/User/View/Helper/UserAvatarHelper.php'
);