<?php
return array (
  'doctrine' => 
  array (
    'configuration' => 
    array (
      'orm_default' => 
      array (
        'types' => 
        array (
          'email' => 'BoilerAppDb\\Doctrine\\DBAL\\Types\\EmailType',
          'md5hash' => 'BoilerAppDb\\Doctrine\\DBAL\\Types\\Md5HashType',
          'authaccessstateenum' => 'BoilerAppAccessControl\\Doctrine\\DBAL\\Types\\AuthAccessStateEnumType',
        ),
        'metadata_cache' => 'array',
        'query_cache' => 'array',
        'result_cache' => 'array',
        'driver' => 'orm_default',
        'generate_proxies' => true,
        'proxy_dir' => 'data/DoctrineORMModule/Proxy',
        'proxy_namespace' => 'DoctrineORMModule\\Proxy',
        'filters' => 
        array (
        ),
        'datetime_functions' => 
        array (
        ),
        'string_functions' => 
        array (
        ),
        'numeric_functions' => 
        array (
        ),
      ),
    ),
    'connection' => 
    array (
      'orm_default' => 
      array (
        'driverClass' => 'Doctrine\\DBAL\\Driver\\PDOMySql\\Driver',
        'doctrineTypeMappings' => 
        array (
          'enum' => 'string',
        ),
        'configuration' => 'orm_default',
        'eventmanager' => 'orm_default',
        'params' => 
        array (
          'host' => 'localhost',
          'port' => '3306',
          'user' => 'root',
          'password' => '',
          'dbname' => 'sample-application',
        ),
      ),
    ),
    'cache' => 
    array (
      'apc' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\ApcCache',
        'namespace' => 'DoctrineModule',
      ),
      'array' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\ArrayCache',
        'namespace' => 'DoctrineModule',
      ),
      'filesystem' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\FilesystemCache',
        'directory' => 'data/DoctrineModule/cache',
        'namespace' => 'DoctrineModule',
      ),
      'memcache' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\MemcacheCache',
        'instance' => 'my_memcache_alias',
        'namespace' => 'DoctrineModule',
      ),
      'memcached' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\MemcachedCache',
        'instance' => 'my_memcached_alias',
        'namespace' => 'DoctrineModule',
      ),
      'redis' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\RedisCache',
        'instance' => 'my_redis_alias',
        'namespace' => 'DoctrineModule',
      ),
      'wincache' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\WinCacheCache',
        'namespace' => 'DoctrineModule',
      ),
      'xcache' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\XcacheCache',
        'namespace' => 'DoctrineModule',
      ),
      'zenddata' => 
      array (
        'class' => 'Doctrine\\Common\\Cache\\ZendDataCache',
        'namespace' => 'DoctrineModule',
      ),
    ),
    'authentication' => 
    array (
      'odm_default' => 
      array (
      ),
      'orm_default' => 
      array (
        'objectManager' => 'doctrine.entitymanager.orm_default',
      ),
    ),
    'authenticationadapter' => 
    array (
      'odm_default' => true,
      'orm_default' => true,
    ),
    'authenticationstorage' => 
    array (
      'odm_default' => true,
      'orm_default' => true,
    ),
    'authenticationservice' => 
    array (
      'odm_default' => true,
      'orm_default' => true,
    ),
    'driver' => 
    array (
      'orm_default' => 
      array (
        'class' => 'Doctrine\\ORM\\Mapping\\Driver\\DriverChain',
        'drivers' => 
        array (
          'BoilerAppAccessControl\\Entity' => 'BoilerAppAccessControl_driver',
          'BoilerAppUser\\Entity' => 'user_driver',
        ),
      ),
      'BoilerAppAccessControl_driver' => 
      array (
        'class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
        'cache' => 'array',
        'paths' => 
        array (
          0 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../src/BoilerAppAccessControl/Entity',
        ),
      ),
      'user_driver' => 
      array (
        'class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
        'cache' => 'array',
        'paths' => 
        array (
          0 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-user\\config/../src/BoilerAppUser/Entity',
        ),
      ),
    ),
    'entitymanager' => 
    array (
      'orm_default' => 
      array (
        'connection' => 'orm_default',
        'configuration' => 'orm_default',
      ),
    ),
    'eventmanager' => 
    array (
      'orm_default' => 
      array (
      ),
    ),
    'sql_logger_collector' => 
    array (
      'orm_default' => 
      array (
      ),
    ),
    'mapping_collector' => 
    array (
      'orm_default' => 
      array (
      ),
    ),
    'formannotationbuilder' => 
    array (
      'orm_default' => 
      array (
      ),
    ),
    'entity_resolver' => 
    array (
      'orm_default' => 
      array (
      ),
    ),
    'migrations_configuration' => 
    array (
      'orm_default' => 
      array (
        'directory' => 'data/DoctrineORMModule/Migrations',
        'namespace' => 'DoctrineORMModule\\Migrations',
        'table' => 'migrations',
      ),
    ),
    'migrations_cmd' => 
    array (
      'generate' => 
      array (
      ),
      'execute' => 
      array (
      ),
      'migrate' => 
      array (
      ),
      'status' => 
      array (
      ),
      'version' => 
      array (
      ),
      'diff' => 
      array (
      ),
    ),
  ),
  'service_manager' => 
  array (
    'abstract_factories' => 
    array (
      0 => 'BoilerAppDb\\Factory\\AbstractEntityRepositoryFactory',
      'DoctrineModule' => 'DoctrineModule\\ServiceFactory\\AbstractDoctrineServiceFactory',
    ),
    'factories' => 
    array (
      'doctrine.cli' => 'DoctrineModule\\Service\\CliFactory',
      'Doctrine\\ORM\\EntityManager' => 'DoctrineORMModule\\Service\\EntityManagerAliasCompatFactory',
      'AccessControlAuthenticationService' => 'BoilerAppAccessControl\\Factory\\AccessControlAuthenticationServiceFactory',
      'AuthenticationStorage' => 'BoilerAppAccessControl\\Factory\\AuthenticationStorageFactory',
      'AuthenticationDoctrineAdapter' => 'BoilerAppAccessControl\\Factory\\AuthenticationDoctrineAdapterFactory',
      'ChangeAuthAccessCredentialForm' => 'BoilerAppAccessControl\\Factory\\ChangeAuthAccessCredentialFormFactory',
      'ChangeAuthAccessEmailIdentityForm' => 'BoilerAppAccessControl\\Factory\\ChangeAuthAccessEmailIdentityFormFactory',
      'ChangeAuthAccessUsernameIdentityForm' => 'BoilerAppAccessControl\\Factory\\ChangeAuthAccessUsernameIdentityFormFactory',
      'RemoveAuthAccessForm' => 'BoilerAppAccessControl\\Factory\\RemoveAuthAccessFormFactory',
      'AuthenticateForm' => 'BoilerAppAccessControl\\Factory\\AuthenticateFormFactory',
      'RegisterForm' => 'BoilerAppAccessControl\\Factory\\RegisterFormFactory',
      'ResetCredentialForm' => 'BoilerAppAccessControl\\Factory\\ResetCredentialFormFactory',
      'SessionManager' => 'BoilerAppAccessControl\\Factory\\SessionManagerFactory',
      'SessionContainer' => 'BoilerAppAccessControl\\Factory\\SessionContainerFactory',
      'Captcha' => 'BoilerAppAccessControl\\Factory\\CaptchaFactory',
      'Encryptor' => 'BoilerAppAccessControl\\Factory\\EncryptorFactory',
      'UserService' => 'BoilerAppUser\\Factory\\UserServiceFactory',
      'UserAccountService' => 'BoilerAppUser\\Factory\\UserAccountServiceFactory',
      'UserModel' => 'BoilerAppUser\\Factory\\UserModelFactory',
      'UserProviderModel' => 'BoilerAppUser\\Factory\\UserProviderModelFactory',
      'ChangeUserAvatarForm' => 'BoilerAppUser\\Factory\\ChangeUserAvatarFormFactory',
      'ChangeUserDisplayNameForm' => 'BoilerAppUser\\Factory\\ChangeUserDisplayNameFormFactory',
      'CssFilter' => '\\AssetsBundle\\Factory\\Filter\\CssFilterFactory',
      'JsFilter' => '\\AssetsBundle\\Factory\\Filter\\JsFilterFactory',
      'LessFilter' => '\\AssetsBundle\\Factory\\Filter\\LessFilterFactory',
      'PngFilter' => '\\AssetsBundle\\Factory\\Filter\\PngFilterFactory',
      'JpegFilter' => '\\AssetsBundle\\Factory\\Filter\\JpegFilterFactory',
      'GifFilter' => '\\AssetsBundle\\Factory\\Filter\\GifFilterFactory',
      'AssetsBundleService' => '\\AssetsBundle\\Factory\\ServiceFactory',
      'JsCustomStrategy' => '\\AssetsBundle\\Factory\\JsCustomStrategyFactory',
      'JsCustomRenderer' => '\\AssetsBundle\\Factory\\JsCustomRendererFactory',
      'TemplatingService' => 'TreeLayoutStack\\Factory\\TemplatingServiceFactory',
      'Translator' => 'BoilerAppDisplay\\Factory\\TranslatorFactory',
      'MessengerService' => 'BoilerAppMessenger\\Factory\\MessengerServiceFactory',
      'MailMessageTransporter' => 'BoilerAppMessenger\\Factory\\MailMessageTransporterFactory',
      'MailMessageRenderer' => 'BoilerAppMessenger\\Factory\\MailMessageRendererFactory',
      'StyleInlinerService' => 'BoilerAppMessenger\\Factory\\StyleInlinerFactory',
      'InlineStyleProcessor' => 'BoilerAppMessenger\\Factory\\InlineStyleProcessorFactory',
      'CssToInlineStylesProcessor' => 'BoilerAppMessenger\\Factory\\CssToInlineStylesProcessorFactory',
      'social' => 
      Closure::__set_state(array(
      )),
      'Logger' => 
      Closure::__set_state(array(
      )),
    ),
    'invokables' => 
    array (
      'doctrine.dbal_cmd.runsql' => '\\Doctrine\\DBAL\\Tools\\Console\\Command\\RunSqlCommand',
      'doctrine.dbal_cmd.import' => '\\Doctrine\\DBAL\\Tools\\Console\\Command\\ImportCommand',
      'doctrine.orm_cmd.clear_cache_metadata' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\ClearCache\\MetadataCommand',
      'doctrine.orm_cmd.clear_cache_result' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\ClearCache\\ResultCommand',
      'doctrine.orm_cmd.clear_cache_query' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\ClearCache\\QueryCommand',
      'doctrine.orm_cmd.schema_tool_create' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\SchemaTool\\CreateCommand',
      'doctrine.orm_cmd.schema_tool_update' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\SchemaTool\\UpdateCommand',
      'doctrine.orm_cmd.schema_tool_drop' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\SchemaTool\\DropCommand',
      'doctrine.orm_cmd.convert_d1_schema' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\ConvertDoctrine1SchemaCommand',
      'doctrine.orm_cmd.generate_entities' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\GenerateEntitiesCommand',
      'doctrine.orm_cmd.generate_proxies' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\GenerateProxiesCommand',
      'doctrine.orm_cmd.convert_mapping' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\ConvertMappingCommand',
      'doctrine.orm_cmd.run_dql' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\RunDqlCommand',
      'doctrine.orm_cmd.validate_schema' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\ValidateSchemaCommand',
      'doctrine.orm_cmd.info' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\InfoCommand',
      'doctrine.orm_cmd.ensure_production_settings' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\EnsureProductionSettingsCommand',
      'doctrine.orm_cmd.generate_repositories' => '\\Doctrine\\ORM\\Tools\\Console\\Command\\GenerateRepositoriesCommand',
      'AccessControlService' => 'BoilerAppAccessControl\\Service\\AccessControlService',
      'AuthAccessService' => 'BoilerAppAccessControl\\Service\\AuthAccessService',
      'AuthenticationService' => 'BoilerAppAccessControl\\Service\\AuthenticationService',
      'RegistrationService' => 'BoilerAppAccessControl\\Service\\RegistrationService',
    ),
    'aliases' => 
    array (
      'Zend\\Authentication\\AuthenticationService' => 'AccessControlAuthenticationService',
    ),
  ),
  'doctrine_factories' => 
  array (
    'cache' => 'DoctrineModule\\Service\\CacheFactory',
    'eventmanager' => 'DoctrineModule\\Service\\EventManagerFactory',
    'driver' => 'DoctrineModule\\Service\\DriverFactory',
    'authenticationadapter' => 'DoctrineModule\\Service\\Authentication\\AdapterFactory',
    'authenticationstorage' => 'DoctrineModule\\Service\\Authentication\\StorageFactory',
    'authenticationservice' => 'DoctrineModule\\Service\\Authentication\\AuthenticationServiceFactory',
    'connection' => 'DoctrineORMModule\\Service\\DBALConnectionFactory',
    'configuration' => 'DoctrineORMModule\\Service\\ConfigurationFactory',
    'entitymanager' => 'DoctrineORMModule\\Service\\EntityManagerFactory',
    'entity_resolver' => 'DoctrineORMModule\\Service\\EntityResolverFactory',
    'sql_logger_collector' => 'DoctrineORMModule\\Service\\SQLLoggerCollectorFactory',
    'mapping_collector' => 'DoctrineORMModule\\Service\\MappingCollectorFactory',
    'formannotationbuilder' => 'DoctrineORMModule\\Service\\FormAnnotationBuilderFactory',
    'migrations_configuration' => 'DoctrineORMModule\\Service\\MigrationsConfigurationFactory',
    'migrations_cmd' => 'DoctrineORMModule\\Service\\MigrationsCommandFactory',
  ),
  'controllers' => 
  array (
    'factories' => 
    array (
      'DoctrineModule\\Controller\\Cli' => 'DoctrineModule\\Service\\CliControllerFactory',
    ),
    'invokables' => 
    array (
      'BoilerAppAccessControl\\Controller\\Registration' => 'BoilerAppAccessControl\\Controller\\RegistrationController',
      'BoilerAppAccessControl\\Controller\\Authentication' => 'BoilerAppAccessControl\\Controller\\AuthenticationController',
      'BoilerAppAccessControl\\Controller\\AuthAccess' => 'BoilerAppAccessControl\\Controller\\AuthAccessController',
      'BoilerAppUser\\Controller\\UserAccount' => 'BoilerAppUser\\Controller\\UserAccountController',
      'AssetsBundle\\Controller\\Tools' => 'AssetsBundle\\Controller\\ToolsController',
      'Application\\Controller\\Index' => 'Application\\Controller\\IndexController',
    ),
  ),
  'route_manager' => 
  array (
    'factories' => 
    array (
      'symfony_cli' => 'DoctrineModule\\Service\\SymfonyCliRouteFactory',
    ),
  ),
  'console' => 
  array (
    'router' => 
    array (
      'routes' => 
      array (
        'doctrine_cli' => 
        array (
          'type' => 'symfony_cli',
        ),
        'render-assets' => 
        array (
          'options' => 
          array (
            'route' => 'render',
            'defaults' => 
            array (
              'controller' => 'AssetsBundle\\Controller\\Tools',
              'action' => 'renderAssets',
            ),
          ),
        ),
        'empty-cache' => 
        array (
          'options' => 
          array (
            'route' => 'empty',
            'defaults' => 
            array (
              'controller' => 'AssetsBundle\\Controller\\Tools',
              'action' => 'emptyCache',
            ),
          ),
        ),
      ),
    ),
  ),
  'form_elements' => 
  array (
    'aliases' => 
    array (
      'objectselect' => 'DoctrineModule\\Form\\Element\\ObjectSelect',
      'objectradio' => 'DoctrineModule\\Form\\Element\\ObjectRadio',
      'objectmulticheckbox' => 'DoctrineModule\\Form\\Element\\ObjectMultiCheckbox',
    ),
    'factories' => 
    array (
      'DoctrineModule\\Form\\Element\\ObjectSelect' => 'DoctrineORMModule\\Service\\ObjectSelectFactory',
      'DoctrineModule\\Form\\Element\\ObjectRadio' => 'DoctrineORMModule\\Service\\ObjectRadioFactory',
      'DoctrineModule\\Form\\Element\\ObjectMultiCheckbox' => 'DoctrineORMModule\\Service\\ObjectMultiCheckboxFactory',
    ),
  ),
  'hydrators' => 
  array (
    'factories' => 
    array (
      'DoctrineModule\\Stdlib\\Hydrator\\DoctrineObject' => 'DoctrineORMModule\\Service\\DoctrineObjectHydratorFactory',
    ),
  ),
  'router' => 
  array (
    'routes' => 
    array (
      'doctrine_orm_module_yuml' => 
      array (
        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
        'options' => 
        array (
          'route' => '/ocra_service_manager_yuml',
          'defaults' => 
          array (
            'controller' => 'DoctrineORMModule\\Yuml\\YumlController',
            'action' => 'index',
          ),
        ),
      ),
      'AccessControl' => 
      array (
        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
        'options' => 
        array (
          'route' => '/access-control',
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'AuthAccess' => 
          array (
            'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
            'options' => 
            array (
              'route' => '/auth-access',
              'defaults' => 
              array (
                'controller' => 'BoilerAppAccessControl\\Controller\\AuthAccess',
                'action' => 'index',
              ),
            ),
            'may_terminate' => true,
            'child_routes' => 
            array (
              'ChangeEmailIdentity' => 
              array (
                'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
                'options' => 
                array (
                  'route' => '/change-email-identity',
                  'defaults' => 
                  array (
                    'controller' => 'BoilerAppAccessControl\\Controller\\AuthAccess',
                    'action' => 'changeEmailIdentity',
                  ),
                ),
              ),
              'ConfirmChangeEmailIdentity' => 
              array (
                'type' => 'Zend\\Mvc\\Router\\Http\\Segment',
                'options' => 
                array (
                  'route' => '/confirm-change-email-identity/:public_key/:email_identity',
                  'defaults' => 
                  array (
                    'controller' => 'BoilerAppAccessControl\\Controller\\AuthAccess',
                    'action' => 'confirmChangeEmailIdentity',
                  ),
                ),
              ),
              'ChangeUsernameIdentity' => 
              array (
                'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
                'options' => 
                array (
                  'route' => '/change-username-identity',
                  'defaults' => 
                  array (
                    'controller' => 'BoilerAppAccessControl\\Controller\\AuthAccess',
                    'action' => 'changeUsernameIdentity',
                  ),
                ),
              ),
              'ChangeCredential' => 
              array (
                'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
                'options' => 
                array (
                  'route' => '/change-credential',
                  'defaults' => 
                  array (
                    'controller' => 'BoilerAppAccessControl\\Controller\\AuthAccess',
                    'action' => 'changeCredential',
                  ),
                ),
              ),
              'RemoveAuthAccess' => 
              array (
                'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
                'options' => 
                array (
                  'route' => '/remove-auth-access',
                  'defaults' => 
                  array (
                    'controller' => 'BoilerAppAccessControl\\Controller\\AuthAccess',
                    'action' => 'removeAuthAccess',
                  ),
                ),
              ),
            ),
          ),
          'Register' => 
          array (
            'type' => 'Zend\\Mvc\\Router\\Http\\Segment',
            'options' => 
            array (
              'route' => '/register[/:service]',
              'defaults' => 
              array (
                'controller' => 'BoilerAppAccessControl\\Controller\\Registration',
                'action' => 'register',
              ),
            ),
          ),
          'CheckEmailIdentityAvailability' => 
          array (
            'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
            'options' => 
            array (
              'route' => '/check-email-identity-availability',
              'defaults' => 
              array (
                'controller' => 'BoilerAppAccessControl\\Controller\\Registration',
                'action' => 'checkEmailIdentityAvailability',
              ),
            ),
          ),
          'CheckUsernameIdentityAvailability' => 
          array (
            'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
            'options' => 
            array (
              'route' => '/check-username-identity-availability',
              'defaults' => 
              array (
                'controller' => 'BoilerAppAccessControl\\Controller\\Registration',
                'action' => 'checkUsernameIdentityAvailability',
              ),
            ),
          ),
          'ConfirmEmail' => 
          array (
            'type' => 'Zend\\Mvc\\Router\\Http\\Segment',
            'options' => 
            array (
              'route' => '/confirm-email/:public_key/:email_identity',
              'defaults' => 
              array (
                'controller' => 'BoilerAppAccessControl\\Controller\\Registration',
                'action' => 'confirmEmail',
              ),
            ),
          ),
          'ResendConfirmationEmail' => 
          array (
            'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
            'options' => 
            array (
              'route' => '/resend-confirmation-email',
              'defaults' => 
              array (
                'controller' => 'BoilerAppAccessControl\\Controller\\Registration',
                'action' => 'resendConfirmationEmail',
              ),
            ),
          ),
          'Authenticate' => 
          array (
            'type' => 'Zend\\Mvc\\Router\\Http\\Segment',
            'options' => 
            array (
              'route' => '/authenticate[/:service]',
              'defaults' => 
              array (
                'controller' => 'BoilerAppAccessControl\\Controller\\Authentication',
                'action' => 'authenticate',
              ),
            ),
          ),
          'ForgottenCredential' => 
          array (
            'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
            'options' => 
            array (
              'route' => '/forgotten-credential',
              'defaults' => 
              array (
                'controller' => 'BoilerAppAccessControl\\Controller\\Authentication',
                'action' => 'forgottenCredential',
              ),
            ),
          ),
          'ResetCredential' => 
          array (
            'type' => 'Zend\\Mvc\\Router\\Http\\Segment',
            'options' => 
            array (
              'route' => '/reset-credential/:public_key/:email_identity',
              'defaults' => 
              array (
                'controller' => 'BoilerAppAccessControl\\Controller\\Authentication',
                'action' => 'resetCredential',
              ),
            ),
          ),
          'Logout' => 
          array (
            'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
            'options' => 
            array (
              'route' => '/logout',
              'defaults' => 
              array (
                'controller' => 'BoilerAppAccessControl\\Controller\\Authentication',
                'action' => 'logout',
              ),
            ),
          ),
        ),
      ),
      'User' => 
      array (
        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
        'options' => 
        array (
          'route' => '/user',
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'Account' => 
          array (
            'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
            'options' => 
            array (
              'route' => '/account',
              'defaults' => 
              array (
                'controller' => 'BoilerAppUser\\Controller\\UserAccount',
                'action' => 'index',
              ),
            ),
            'may_terminate' => true,
            'child_routes' => 
            array (
              'ChangeAvatar' => 
              array (
                'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
                'options' => 
                array (
                  'route' => '/change-avatar',
                  'defaults' => 
                  array (
                    'controller' => 'BoilerAppUser\\Controller\\UserAccount',
                    'action' => 'changeAvatar',
                  ),
                ),
              ),
              'ChangeDisplayName' => 
              array (
                'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
                'options' => 
                array (
                  'route' => '/change-display-name',
                  'defaults' => 
                  array (
                    'controller' => 'BoilerAppUser\\Controller\\UserAccount',
                    'action' => 'changeDisplayName',
                  ),
                ),
              ),
              'CheckDisplayNameAvailability' => 
              array (
                'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
                'options' => 
                array (
                  'route' => '/check-display-name-availability',
                  'defaults' => 
                  array (
                    'controller' => 'BoilerAppUser\\Controller\\UserAccount',
                    'action' => 'checkDisplayNameAvailability',
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
      'jscustom' => 
      array (
        'type' => 'literal',
        'options' => 
        array (
          'route' => '/jscustom',
        ),
        'may_terminate' => true,
        'child_routes' => 
        array (
          'definition' => 
          array (
            'type' => 'Zend\\Mvc\\Router\\Http\\Segment',
            'options' => 
            array (
              'route' => '/:controller/:js_action',
              'contraints' => 
              array (
                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                'js_action' => '[a-zA-Z][a-zA-Z0-9_-]*',
              ),
              'defaults' => 
              array (
                'action' => 'jscustom',
              ),
            ),
          ),
        ),
      ),
      'Home' => 
      array (
        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
        'options' => 
        array (
          'route' => '/',
          'defaults' => 
          array (
            'controller' => 'Application\\Controller\\Index',
            'action' => 'index',
          ),
        ),
      ),
      'Terms' => 
      array (
        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
        'options' => 
        array (
          'route' => '/terms',
          'defaults' => 
          array (
            'controller' => 'Application\\Controller\\Index',
            'action' => 'terms',
          ),
        ),
      ),
      'Privacy' => 
      array (
        'type' => 'Zend\\Mvc\\Router\\Http\\Literal',
        'options' => 
        array (
          'route' => '/privacy',
          'defaults' => 
          array (
            'controller' => 'Application\\Controller\\Index',
            'action' => 'privacy',
          ),
        ),
      ),
    ),
  ),
  'view_manager' => 
  array (
    'template_map' => 
    array (
      'zend-developer-tools/toolbar/doctrine-orm-queries' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\doctrine\\doctrine-orm-module\\config/../view/zend-developer-tools/toolbar/doctrine-orm-queries.phtml',
      'zend-developer-tools/toolbar/doctrine-orm-mappings' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\doctrine\\doctrine-orm-module\\config/../view/zend-developer-tools/toolbar/doctrine-orm-mappings.phtml',
      'error/404' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-display\\config/../view/error/404.phtml',
      'error/index' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-display\\config/../view/error/index.phtml',
      'layout/layout' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\module\\Application\\config/../view/layout/layout.phtml',
      'layout/default' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\module\\Application\\config/../view/layout/default.phtml',
      'header/logged' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\module\\Application\\config/../view/application/header/logged.phtml',
      'header/unlogged' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\module\\Application\\config/../view/application/header/unlogged.phtml',
      'footer/footer' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\module\\Application\\config/../view/application/footer/footer.phtml',
      'application/index/index' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\module\\Application\\config/../view/application/index/index.phtml',
      'application/index/terms' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\module\\Application\\config/../view/application/index/terms.phtml',
      'application/index/privacy' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\module\\Application\\config/../view/application/index/privacy.phtml',
    ),
    'template_path_stack' => 
    array (
      'boiler-app-access-control' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../view',
      'User' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-user\\config/../view',
      0 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-display\\config/../view',
    ),
    'strategies' => 
    array (
      0 => 'JsCustomStrategy',
      1 => 'ViewJsonStrategy',
    ),
    'display_not_found_reason' => true,
    'display_exceptions' => true,
    'doctype' => 'HTML5',
    'not_found_template' => 'error/404',
    'exception_template' => 'error/index',
  ),
  'zenddevelopertools' => 
  array (
    'profiler' => 
    array (
      'collectors' => 
      array (
        'doctrine.sql_logger_collector.orm_default' => 'doctrine.sql_logger_collector.orm_default',
        'doctrine.mapping_collector.orm_default' => 'doctrine.mapping_collector.orm_default',
      ),
    ),
    'toolbar' => 
    array (
      'entries' => 
      array (
        'doctrine.sql_logger_collector.orm_default' => 'zend-developer-tools/toolbar/doctrine-orm-queries',
        'doctrine.mapping_collector.orm_default' => 'zend-developer-tools/toolbar/doctrine-orm-mappings',
      ),
    ),
  ),
  'SessionNamespace' => 'BoilerAppAccessControl',
  'asset_bundle' => 
  array (
    'assets' => 
    array (
      'BoilerAppAccessControl\\Controller\\Registration' => 
      array (
        'register' => 
        array (
          'js' => 
          array (
            0 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../assets/js/Validator/auth-access-identities.js',
            1 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../assets/js/Controller/AccessControlIdentityAwareController.js',
            2 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../assets/js/Controller/RegistrationRegisterController.js',
            3 => '@zfRootPath/vendor/nak5ive/Form.PasswordStrength/Source/Form.PasswordStrength.js',
            4 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../assets/js/Behavior/Form.PasswordStrength.js',
          ),
        ),
      ),
      'BoilerAppAccessControl\\Controller\\Authentication' => 
      array (
        'js' => 
        array (
          0 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../assets/js/Controller/AuthenticationAuthenticateController.js',
        ),
      ),
      'BoilerAppAccessControl\\Controller\\AuthAccess' => 
      array (
        'index' => 
        array (
          'js' => 
          array (
            0 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../assets/js/Validator/auth-access-identities.js',
            1 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../assets/js/Controller/AccessControlIdentityAwareController.js',
            2 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../assets/js/Controller/AuthAccessIndexController.js',
            3 => '@zfRootPath/vendor/nak5ive/Form.PasswordStrength/Source/Form.PasswordStrength.js',
            4 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../assets/js/Behavior/Form.PasswordStrength.js',
          ),
        ),
      ),
      'BoilerAppUser\\Controller\\UserAccount' => 
      array (
        'js' => 
        array (
          0 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-user\\config/../assets/js/Validator/user-display-name.js',
          1 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-user\\config/../assets/js/Controller/UserAccountIndexController.js',
        ),
      ),
      'less' => 
      array (
        0 => '@zfRootPath/vendor/twitter/bootstrap/less/bootstrap.less',
        1 => '@zfRootPath/vendor/fortawesome/font-awesome/less/font-awesome.less',
        2 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-display\\config/../assets/less/bootstrap-custom.less',
        3 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-display\\config/../assets/less/global.less',
      ),
      'js' => 
      array (
        0 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-display\\config/../assets/js/mootools/mootools-core-1.4.5.js',
        1 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-display\\config/../assets/js/mootools/mootools-more-1.4.0.1.js',
        2 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-display\\config/../assets/js/mootools/mootools-bootstrap.js',
        3 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-display\\config/../assets/js/mootools',
        4 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-display\\config/../assets/js/modernizr.min.js',
        5 => '@zfRootPath/vendor/fabiomcosta/mootools-meio-mask/Source/Meio.Mask.js',
        6 => '@zfRootPath/vendor/fabiomcosta/mootools-meio-mask/Source',
        7 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-display\\config/../assets/js/MeioMask/behavior.js',
        8 => '@zfRootPath/vendor/arian/iFrameFormRequest/Source/iFrameFormRequest.js',
        9 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-display\\config/../assets/js/controller.js',
        10 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-display\\config/../assets/js/Modal',
        11 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-display\\config/../assets/js/Validator/common.js',
      ),
      'media' => 
      array (
        0 => '@zfRootPath/vendor/fortawesome/font-awesome/font',
        1 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-display\\config/../assets/images',
        2 => '@zfRootPath/vendor/twitter/bootstrap/img',
      ),
      'BoilerAppMessenger' => 
      array (
        'mail' => 
        array (
          'css' => 
          array (
            0 => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-messenger\\config/../assets/css/reset.css',
          ),
        ),
      ),
    ),
    'production' => true,
    'lastModifiedTime' => NULL,
    'cachePath' => '@zfRootPath/public/assets/cache',
    'assetsPath' => '@zfRootPath/public/assets',
    'cacheUrl' => '@zfBaseUrl/assets/cache/',
    'mediaExt' => 
    array (
      0 => 'jpeg',
      1 => 'jpg',
      2 => 'png',
      3 => 'gif',
      4 => 'cur',
      5 => 'ttf',
      6 => 'eot',
      7 => 'svg',
      8 => 'woff',
    ),
    'recursiveSearch' => false,
    'rendererToStrategy' => 
    array (
      'Zend\\View\\Renderer\\PhpRenderer' => '\\AssetsBundle\\View\\Strategy\\ViewHelperStrategy',
      'Zend\\View\\Renderer\\FeedRenderer' => '\\AssetsBundle\\View\\Strategy\\NoneStrategy',
      'Zend\\View\\Renderer\\JsonRenderer' => '\\AssetsBundle\\View\\Strategy\\NoneStrategy',
      'BoilerAppMessenger\\Media\\Mail\\MailMessageRenderer' => '\\AssetsBundle\\View\\Strategy\\ViewHelperStrategy',
    ),
    'filters' => 
    array (
      'css' => 'CssFilter',
      'js' => 'JsFilter',
      'less' => 'LessFilter',
      'png' => 'PngFilter',
      'jpg' => 'JpegFilter',
      'jpeg' => 'JpegFilter',
      'gif' => 'GifFilter',
    ),
  ),
  'translator' => 
  array (
    'translation_file_patterns' => 
    array (
      0 => 
      array (
        'type' => 'phparray',
        'base_dir' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../languages',
        'pattern' => '%s/Common.php',
      ),
      1 => 
      array (
        'type' => 'phparray',
        'base_dir' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../languages',
        'pattern' => '%s/Validate.php',
        'text_domain' => 'validator',
      ),
      2 => 
      array (
        'type' => 'phparray',
        'base_dir' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../languages',
        'pattern' => '%s/Form.PasswordStrength.php',
        'text_domain' => 'validator',
      ),
      3 => 
      array (
        'type' => 'phparray',
        'base_dir' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-user\\config/../languages',
        'pattern' => '%s/Common.php',
      ),
      4 => 
      array (
        'type' => 'phparray',
        'base_dir' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-user\\config/../languages',
        'pattern' => '%s/Validate.php',
        'text_domain' => 'validator',
      ),
      5 => 
      array (
        'type' => 'phparray',
        'base_dir' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-display\\config/../languages',
        'pattern' => '%s/Common.php',
      ),
      6 => 
      array (
        'type' => 'phparray',
        'base_dir' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\module\\Application\\config/../languages',
        'pattern' => '%s/Common.php',
      ),
    ),
    'locale' => 'fr_FR',
  ),
  'medias' => 
  array (
    'mail' => 
    array (
      'template_map' => 
      array (
        'mail/auth-access/confirm-change-email-identity' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../view/mail/auth-access/confirm-change-email-identity.phtml',
        'mail/authentication/confirm-reset-credential' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../view/mail/authentication/confirm-reset-credential.phtml',
        'mail/authentication/credential-reset' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../view/mail/authentication/credential-reset.phtml',
        'mail/registration/confirm-email' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../view/mail/registration/confirm-email.phtml',
        'mail/layout' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\module\\Application\\config/../view/mail/layout.phtml',
        'mail/header' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\module\\Application\\config/../view/mail/header.phtml',
        'mail/footer' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\module\\Application\\config/../view/mail/footer.phtml',
      ),
      'mail_transporter' => 'Zend\\Mail\\Transport\\Sendmail',
      'tree_layout_stack' => 
      array (
        'layout_tree' => 
        array (
          'default' => 
          array (
            'template' => 'mail/layout',
            'children' => 
            array (
              'header' => 'mail/header',
              'footer' => 'mail/footer',
            ),
          ),
        ),
      ),
    ),
  ),
  'captcha' => 
  array (
    'fontDir' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-access-control\\config/../data/fonts',
    'font' => 'arial.ttf',
    'fsize' => 30,
    'width' => 220,
    'height' => 70,
    'dotNoiseLevel' => 40,
    'lineNoiseLevel' => 3,
    'wordlen' => 6,
    'imgDir' => './public/assets/captcha',
    'imgUrl' => '/assets/captcha/',
  ),
  'authentication' => 
  array (
    'storage' => 'AuthenticationStorage',
    'adapters' => 
    array (
      'LocalAuth' => 'AuthenticationDoctrineAdapter',
    ),
    'defaultRedirect' => 'Home',
  ),
  'controller_plugins' => 
  array (
    'invokables' => 
    array (
      'RedirectUser' => 'BoilerAppAccessControl\\Mvc\\Controller\\Plugin\\RedirectUser',
    ),
  ),
  'paths' => 
  array (
    'avatarsPath' => 'C:\\Program Files (x86)\\EasyPHP-DevServer-13.1VC9\\data\\localweb\\SampleApplication\\vendor\\zf2-boiler-app\\app-user\\config/../data/avatars',
  ),
  'view_helpers' => 
  array (
    'factories' => 
    array (
      'userAvatar' => 'BoilerAppUser\\Factory\\UserAvatarHelperFactory',
      'Social' => 
      Closure::__set_state(array(
      )),
      'jsController' => 'BoilerAppDisplay\\Factory\\JsControllerHelperFactory',
    ),
    'invokables' => 
    array (
      'alert' => 'TwbBundle\\View\\Helper\\TwbBundleAlert',
      'form' => 'BoilerAppDisplay\\Form\\View\\Helper\\FormHelper',
      'formButton' => 'TwbBundle\\Form\\View\\Helper\\TwbBundleFormButton',
      'formCollection' => 'TwbBundle\\Form\\View\\Helper\\TwbBundleFormCollection',
      'formRow' => 'TwbBundle\\Form\\View\\Helper\\TwbBundleFormRow',
      'formElementErrors' => 'BoilerAppDisplay\\Form\\View\\Helper\\FormElementErrors',
      'escapeJson' => 'BoilerAppDisplay\\View\\Helper\\EscapeJsonHelper',
    ),
  ),
  'tree_layout_stack' => 
  array (
    'layout_tree' => 
    array (
      'default' => 
      array (
        'template' => 'layout/layout',
        'children' => 
        array (
          'specialLayout' => 
          array (
            'template' => 'layout/default',
            'children' => 
            array (
              'header' => 
              Closure::__set_state(array(
              )),
              'footer' => 'footer/footer',
            ),
          ),
        ),
      ),
    ),
  ),
  'messenger' => 
  array (
    'transporters' => 
    array (
      'mail' => 
      Closure::__set_state(array(
      )),
    ),
    'system_user' => 
    array (
      'email' => 'system@sample-application.com',
      'display_name' => 'Sample Application',
    ),
  ),
  'style_inliner' => 
  array (
    'processor' => 'CssToInlineStylesProcessor',
  ),
  'social' => 
  array (
    'google' => 
    array (
      'id' => 'google_id',
      'key' => 'google_key',
    ),
    'twitter' => 
    array (
      'id' => 'twitter_id',
      'key' => 'twitter_key',
    ),
    'facebook' => 
    array (
      'id' => 'facebook_id',
      'key' => 'facebook_key',
    ),
    'google_analytics' => 
    array (
      'id' => 'analytics_id',
    ),
  ),
  'db' => 
  array (
    'username' => 'root',
    'password' => '',
    'dsn' => 'mysql:dbname=sample-app;host=localhost',
  ),
);