<?php
/* TODO Remove Error log */error_log(print_r($_SERVER['REQUEST_URI'],true));
chdir(dirname(__DIR__));

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
