<?php
namespace AccessControl\Validator;
class EmailAddressAvailabilityValidator extends \Zend\Validator\AbstractValidator implements \Zend\ServiceManager\ServiceLocatorAwareInterface{
	use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    const SAME_AS_CURRENTLY_USED = 'emailAddressSameAsCurrentlyUsed';
	const UNAVAILABLE  = 'emailAddressInvalidUnavailable';

    /**
     * @var array
     */
    protected $messageTemplates = array(
    	self::SAME_AS_CURRENTLY_USED => 'The email address "%value%" is the same as currently used',
        self::UNAVAILABLE => 'The email adress "%value%" is unavailable'
    );

    /**
	 * @var string
     */
    protected $currentEmail;

    /**
     * @param string $sCurrentEmail
     * @throws \InvalidArgumentException
     * @return \AccessControl\Validator\EmailAddressAvailabilityValidator
     */
    public function setCurrentEmail($sCurrentEmail){
    	if(!filter_var($sCurrentEmail,FILTER_VALIDATE_EMAIL))throw new \InvalidArgumentException(sprintf(
    		'Current email expects valid email string, "%s" given',
    		$sCurrentEmail
    	));
		$this->currentEmail = $sCurrentEmail;
    	return $this;
    }

    /**
	 * @return string|null
     */
    public function getCurrentEmail(){
    	return $this->currentEmail;
    }

    /**
     * @param string $sEmail
     * @return boolean
     */
    public function checkUserEmailAvailability($sEmail){
    	return $this->getServiceLocator()->get('AccessControlService')->isUserEmailAvailable($sEmail);
    }

	/**
	 * Check if Email is the same as current email (if defined)
	 * @param string $sEmail
	 * @return boolean
	 */
    public function sameAsCurrentlyUsed($sEmail){
    	return ($sCurrentEmail = $this->getCurrentEmail())?$sCurrentEmail === $sEmail:false;
    }

    /**
     * Returns true if and only if $sValue is not empty and available.
     * @param string $sValue
     * @return boolean
     */
    public function isValid($sValue){
    	if(empty($sValue)|| !is_string($sValue))return false;
    	if($this->sameAsCurrentlyUsed($sValue)){
    		$this->error(self::SAME_AS_CURRENTLY_USED,$sValue);
    		return false;
    	}
    	if($this->callCheckUserEmailAvailability($sValue))return true;
    	$this->error(self::UNAVAILABLE,$sValue);
    	return false;
    }
}