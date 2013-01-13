<?php
namespace Application\Db\TableGateway\Feature\EventFeature;
class TableGatewayEvent extends \Zend\Db\TableGateway\Feature\EventFeature\TableGatewayEvent{
    
    /**
     * Set the event name
     * @param string $sName
     */
    public function setName($sName){
        $this->name = $sName;
        return $this;
    }

    /**
     * Set the event target/context
     * @param null|string|object $sTarget
     * @return \Application\Db\TableGateway\Feature\EventFeature\TableGatewayEvent
     */
    public function setTarget($oTarget){
        $this->target = $oTarget;
        return $this;
    }

    /**
     * Set event parameters
     * @param array|\ArrayAccess $aParams
     * @return \Application\Db\TableGateway\Feature\EventFeature\TableGatewayEvent
     */
    public function setParams($aParams){
        $this->params = $aParams;
        return $this;
    }

    /**
     * Set a single parameter by key
     * @param string $sName
     * @param mixed $sValue
     * @return \Application\Db\TableGateway\Feature\EventFeature\TableGatewayEvent
     */
    public function setParam($sName, $sValue){
        $this->params[$sName] = $sValue;
        return $this;
    }
}