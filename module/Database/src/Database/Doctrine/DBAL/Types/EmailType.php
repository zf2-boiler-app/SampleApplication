<?php
namespace Database\Doctrine\DBAL\Types;
class EmailType extends \Doctrine\DBAL\Types\StringType{
	/**
	 * @var string
	 */
	protected $name = 'email';

	/**
	 * @var int
	 */
	protected $defaultLength = 250;

	/**
	 * @see \Doctrine\DBAL\Types\Type::getDefaultLength()
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $oPlatform
     * @return int
	 */
	public function getDefaultLength(\Doctrine\DBAL\Platforms\AbstractPlatform $oPlatform){
		return $this->defaultLength;
	}

    /**
     * @see \Doctrine\DBAL\Types\Type::convertToDatabaseValue()
     * @param string $sValue
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $oPlatform
     * @throws \InvalidArgumentException
     * @return string
     */
    public function convertToDatabaseValue($sValue, \Doctrine\DBAL\Platforms\AbstractPlatform $oPlatform){
        if(!filter_var($sValue,FILTER_VALIDATE_EMAIL))throw new \InvalidArgumentException(sprintf(
        	'EmailType expects valid email adress, "%s" given',
        	$sValue
        ));
        return $sValue;
    }

    /**
     * @see \Doctrine\DBAL\Types\Type::getName()
     * @return string
     */
    public function getName(){
        return $this->name;
    }
}