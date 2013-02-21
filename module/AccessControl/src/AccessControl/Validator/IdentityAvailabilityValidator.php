<?php
namespace AccessControl\Validator;
class IdentityAvailabilityValidator extends \Zend\Validator\AbstractValidator{
	const INVALID = 'identityInvalid';
    const SAME_AS_CURRENTLY_USED = 'identitySameAsCurrentlyUsed';
	const UNAVAILABLE  = 'identityUnavailable';

    /**
     * @var array
     */
    protected $messageTemplates = array(
    	self::INVALID => 'Invalid type given. String expected',
    	self::SAME_AS_CURRENTLY_USED => 'The %identityName% "%value%" is the same as currently used',
        self::UNAVAILABLE => 'The %identityName% "%value%" is unavailable'
    );

    protected $messageVariables = array(
    	'identityName' => null,
    );

    /**
     * Default options to set for the validator
     * @var array
     */
    protected $options = array(
    	'currentIdentity' => null,
    	'checkAvailabilityCallback' => null
    );

    /**
     * @param string $sIdentityName
     * @throws \InvalidArgumentException
     * @return \AccessControl\Validator\IdentityAvailabilityValidator
     */
    public function setIdentityName($sIdentityName){
    	if(!is_string($sIdentityName))throw new \InvalidArgumentException(sprintf(
    		'Identity\'s name expects string, "%s" given',
    		gettype($sIdentityName)
    	));
    	$this->messageVariables['identityName'] = $sIdentityName;
    	return $this;
    }

    /**
     * @return string|null
     */
    public function getIdentityName(){
    	return $this->messageVariables['identityName'];
    }

    /**
     * @param string $sCurrentIdentity
     * @throws \InvalidArgumentException
     * @return \AccessControl\Validator\IdentityAvailabilityValidator
     */
    public function setCurrentIdentity($sCurrentIdentity){
    	if(!is_string($sCurrentIdentity))throw new \InvalidArgumentException(sprintf(
    		'Current identity expects string, "%s" given',
    		gettype($sCurrentIdentity)
    	));
		$this->options['currentIdentity'] = $sCurrentIdentity;
    	return $this;
    }

    /**
	 * @return string|null
     */
    public function getCurrentIdentity(){
    	return $this->options['currentIdentity'];
    }

	/**
	 * @param mixed $oCallback
	 * @throws \BadFunctionCallException
	 * @return \AccessControl\Validator\IdentityAvailabilityValidator
	 */
    public function setCheckAvailabilityCallback($oCallback){
    	if(!is_callable($oCallback))throw new \BadFunctionCallException('$oCallback is not callable');
    	$this->options['checkAvailabilityCallback'] = $oCallback;
    	return $this;
    }

    /**
     * @return mixed : valid callback or null
     */
    public function getCheckAvailabilityCallback(){
    	return $this->options['checkAvailabilityCallback'];
    }

    /**
     * @param string $sIdentity
     * @return boolean
     */
    public function checkAvailability($sIdentity){
    	$oCallback = $this->getCheckAvailabilityCallback();
    	return ($oCallback = $this->getCheckAvailabilityCallback())
    		?call_user_func($oCallback,$sIdentity)
    		:false;
    }

	/**
	 * Check if identity is the same as current (if defined)
	 * @param string $sIdentity
	 * @return boolean
	 */
    public function sameAsCurrentlyUsed($sIdentity){
    	return ($sCurrentIdentity = $this->getCurrentIdentity())?$sCurrentIdentity === $sIdentity:false;
    }

    /**
     * Returns true if $sValue is not the same as current (if defined) and if is available
     * @param string $sValue
     * @return boolean
     */
    public function isValid($sValue){
    	if(empty($sValue)|| !is_string($sValue)){
    		$this->error(self::INVALID);
    		return false;
    	}

    	$this->setValue($sValue);

    	if($this->sameAsCurrentlyUsed($sValue)){
    		$this->error(self::SAME_AS_CURRENTLY_USED,$sValue);
    		return false;
    	}
    	if($this->checkAvailability($sValue))return true;
    	$this->error(self::UNAVAILABLE,$sValue);
    	return false;
    }
}