<?php
namespace Application\Db\RowGateway\Feature\EventFeature;
class RowGatewayEvent implements \Zend\EventManager\EventInterface{

    /**
     * @var\Application\Db\RowGateway\AbstractRowGateway
     */
    protected $target = null;

    /**
     * @var null
     */
    protected $name = null;

    /**
     * @var array|\ArrayAccess
     */
    protected $params = array();

    /**
     * Get event name
     * @return string
     */
    public function getName(){
        return $this->name;
    }

    /**
     * Get target/context from which event was triggered
     * @return null|string|object
     */
    public function getTarget(){
        return $this->target;
    }

    /**
     * Get parameters passed to the event
     * @return array|\ArrayAccess
     */
    public function getParams(){
        return $this->params;
    }

    /**
     * Get a single parameter by name
     * @param string $sName
     * @param mixed $sDefault : Default value to return if parameter does not exist
     * @return mixed
     */
    public function getParam($sName, $sDefault = null){
        return isset($this->params[$sName])?$this->params[$sName]:$sDefault;
    }

    /**
     * Set the event name
     * @see \Zend\EventManager\EventInterface::setName()
     * @param string $sName
     * @return \Application\Db\RowGateway\Feature\EventFeature\RowGatewayEvent
     */
    public function setName($sName){
        $this->name = $sName;
        return $this;
    }

    /**
     * Set the event target/context
     * @see \Zend\EventManager\EventInterface::setTarget()
     * @param null|string|object $oTarget
     * @return \Application\Db\RowGateway\Feature\EventFeature\RowGatewayEvent
     */
    public function setTarget($oTarget){
        $this->target = $oTarget;
        return $this;
    }

    /**
     * Set event parameters
     * @param array|\ArrayAccess $aParams
     * @return \Application\Db\RowGateway\Feature\EventFeature\RowGatewayEvent
     */
    public function setParams( $aParams){
        $this->params = $aParams;
        return $this;
    }

    /**
     * Set a single parameter by key
     * @param string $sName
     * @param mixed $sValue
     * @return \Application\Db\RowGateway\Feature\EventFeature\RowGatewayEvent
     */
    public function setParam($sName, $sValue){
        $this->params[$sName] = $sValue;
        return $this;
    }

    /**
     * Indicate whether or not the parent EventManagerInterface should stop propagating events
     * @param boolean $bFlag
     */
    public function stopPropagation($bFlag = true){
        return;
    }

    /**
     * Has this event indicated event propagation should stop?
     * @return bool
     */
    public function propagationIsStopped(){
        return false;
    }
}