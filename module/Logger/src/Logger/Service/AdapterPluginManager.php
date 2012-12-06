<?php
namespace Logger\Service;
class AdapterPluginManager extends \Zend\ServiceManager\AbstractPluginManager{
    /**
     * Default set of adapters.
     * @var array
     */
    protected $invokableClasses = array(
        'db' => '\Logger\Service\Adapter\DbLogAdapter',
    );

    /**
     * Validate the plugin.
     * @param  mixed $plugin
     * @return void
     * @throws Exception\RuntimeException if invalid
     */
    public function validatePlugin($oPlugin){
        if($oPlugin instanceof \Logger\Service\Adapter\LogAdapterInterface)return;
        throw new \Exception(sprintf(
            'Plugin of type %s is invalid; must implement \Logger\Service\Adapter\LogAdapterInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }
}
