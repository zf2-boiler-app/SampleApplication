<?php
//Composer autoloading
if(file_exists($sAutoloadPath = 'vendor/autoload.php'))include $sAutoloadPath;
else throw new \LogicException('Autoload file "'.$sAutoloadPath.'" does not exist');
if(!class_exists('Zend\Loader\AutoloaderFactory'))throw new \RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');