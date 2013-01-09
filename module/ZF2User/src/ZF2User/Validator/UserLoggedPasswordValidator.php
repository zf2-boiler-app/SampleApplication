<?php
namespace ZF2User\Validator;
class UserLoggedPasswordValidator extends \Zend\Validator\AbstractValidator{
    const WRONG_PASSWORD  = 'wrongPassword';

    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::WRONG_PASSWORD => 'The password is wrong'
    );

    protected $options = array(
    	'checkUserLoggedPassword' => null
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
            if(!empty($aOptions))$aTempOptions['checkUserLoggedPassword'] = array_shift($aOptions);
            $aOptions = $aTempOptions;
        }
        parent::__construct($aOptions);
    }

    /**
     * Call "Check User Email Availability" callback
     * @param string $sValue
     * @return boolean
     */
    public function callCheckUserLoggedPassword($sValue){
        if(!isset($this->options['checkUserLoggedPassword']) || !is_callable($this->options['checkUserLoggedPassword'],true))throw new \Exception('checkUserLoggedPassword is not callable');
        return call_user_func($this->options['checkUserLoggedPassword'],$sValue);
    }

    /**
     * @param callable $oCallback
     * @return \ZF2User\Validator\EmailAddressAvailabilityValidator
     */
    public function setCheckUserEmailAvailability($oCallback){
    	$this->options['checkUserLoggedPassword'] = $oCallback;
        return $this;
    }

    /**
     * Returns true if and only if $sValue is correct user logged password
     * @param string $sValue
     * @return boolean
     */
    public function isValid($sValue){
    	if(empty($sValue)|| !is_string($sValue))return false;
    	if($this->callCheckUserLoggedPassword($sValue))return true;
    	$this->error(self::WRONG_PASSWORD);
    	return false;
    }
}