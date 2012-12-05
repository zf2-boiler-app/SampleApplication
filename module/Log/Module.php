<?php
namespace Log;
use Zend\Mvc\MvcEvent,
Zend\Mvc\ModuleRouteListener,
Zend\Mvc\Router\RouteStackInterface,
Application\View\Helper\AbsoluteUrl,
\SLN\Exception;

class Module{
    public function getConfig(){
    	return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig(){
    	return array('Zend\Loader\StandardAutoloader' => array('namespaces' => array(__NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__)));
    }
}