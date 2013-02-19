<?php
namespace Database\Doctrine\DBAL\Types;
abstract class AbstractEnumType extends \Doctrine\DBAL\Types\Type{
	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var array
	 */
    protected $values = array();

    /**
     * @param array $aFieldDeclaration
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $oPlatform
     * @return string
     */
    public function getSqlDeclaration(array $aFieldDeclaration, \Doctrine\DBAL\Platforms\AbstractPlatform $oPlatform){
    	return 'ENUM('.implode(', ', array_map(function($sValue) use ($oPlatform){
        	return $oPlatform->quoteIdentifier($sValue);
       	}, $this->values)).') COMMENT "(DC2Type:'.$this->getName().')"';
    }

    /**
     * @see \Doctrine\DBAL\Types\Type::convertToPHPValue()
     * @param string $sValue
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $oPlatform
     * @return string
     */
    public function convertToPHPValue($sValue, \Doctrine\DBAL\Platforms\AbstractPlatform $oPlatform){
        return $sValue;
    }

    /**
     * @see \Doctrine\DBAL\Types\Type::convertToDatabaseValue()
     * @param string $sValue
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $oPlatform
     * @throws \InvalidArgumentException
     * @return string
     */
    public function convertToDatabaseValue($sValue, \Doctrine\DBAL\Platforms\AbstractPlatform $oPlatform){
        if(!in_array($value, $this->values))throw new \InvalidArgumentException(sprintf(
        	'"%s" EnumType expects "%s" values, "%s" given',
        	$this->getName(),implode(', ',$this->values),$sValue
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