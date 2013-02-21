<?php
namespace Application\Validator;
class NoSpaces extends \Zend\Validator\AbstractValidator{
    const INVALID = 'noSpacesInvalid';
    const HAS_SPACES = 'hasSpaces';

    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::INVALID => 'Invalid type given. String expected',
        self::HAS_SPACES => 'There can be no spaces in this input.'
    );

    /**
     * Returns true if and only if the value does not contain spaces
     * @param mixed $sValue
     * @return boolean
     */
    public function isValid($sValue){
        if(!is_string($sValue)){
            $this->error(self::INVALID);
            return false;
        }

        $this->setValue($sValue);
        if(preg_match('/\s/', $sValue)){
        	$this->error(self::HAS_SPACES);
	        return false;
        }
        return true;
    }
}
