<?php
namespace User\Doctrine\DBAL\Types;
class UserStateEnumType extends \Database\Doctrine\DBAL\Types\AbstractEnumType{
	/**
	 * @var string
	 */
	protected $name = 'userstateenum';

	/**
	 * @var array
	 */
    protected $values = array();
}