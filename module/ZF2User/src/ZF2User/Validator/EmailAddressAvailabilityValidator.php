<?php
namespace ZF2User\Validator;
class EmailAddressAvailabilityValidator extends \Zend\Validator\AbstractValidator{
    const UNAVAILABLE  = 'emailAddressInvalidUnavailable';

    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::UNAVAILABLE => "The email adress is unavailable",
    );
    
    protected $options = array(
    	'checkUserEmailAvailability' => null	
    );

    /**
     * Constructor
     *
     * @param  array|Traversable|int $options OPTIONAL
     */
    public function __construct($aOptions = null){
    	if($aOptions instanceof \Traversable)$aOptions = \Zend\Stdlib\ArrayUtils::iteratorToArray($aOptions);
        if(!is_array($aOptions)){
            $aOptions = func_get_args();
            $aTempOptions = array();
            if(!empty($aOptions))$aTempOptions['checkUserEmailAvailability'] = array_shift($aOptions);
            $aOptions = $aTempOptions;
        }
        parent::__construct($aOptions);
    }

    /**
     * Call "Check User Email Availability" callback
     * @param string $sValue
     * @return boolean
     */
    public function callCheckUserEmailAvailability($sValue){
        if(!isset($this->options['checkUserEmailAvailability']) || !is_callable($this->options['checkUserEmailAvailability'],true))throw new \Exception('checkUserEmailAvailability is not callable');
        return call_user_func($this->options['checkUserEmailAvailability'],$sValue);
    }

    /**
     * @param callable $oUserService
     * @return \ZF2User\Validator\EmailAddressAvailabilityValidator
     */
    public function setCheckUserEmailAvailability($oCallback){
    	$this->options['checkUserEmailAvailability'] = $oCallback;
        return $this;
    }

    /**
     * Returns true if and only if $sValue is not empty and available.
     * @param string $sValue
     * @return boolean
     */
    public function isValid($sValue){
    	if(empty($sValue)|| !is_string($sValue))return false;
    	if($this->callCheckUserEmailAvailability($sValue))return true;
    	$this->error(self::UNAVAILABLE);
    	return false;
    }
}
