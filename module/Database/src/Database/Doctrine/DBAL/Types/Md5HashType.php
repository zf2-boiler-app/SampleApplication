<?php
namespace Database\Doctrine\DBAL\Types;
class Md5HashType extends \Doctrine\DBAL\Types\StringType{
	/**
	 * @var string
	 */
	protected $name = 'md5hash';

	/**
	 * @var int
	 */
	protected $defaultLength = 32;

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
        if(!!preg_match('/^[a-f0-9]{32}$/', $sValue))throw new \InvalidArgumentException(sprintf(
        	'Md5HashType expects valid md5 hash, "%s" given',
        	is_string($sValue)?'not md5 string':gettype($sPassword)
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