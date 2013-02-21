<?php
namespace AccessControl\Doctrine\DBAL\Types;
class AuthAccessStateEnumType extends \Database\Doctrine\DBAL\Types\AbstractEnumType{
	/**
	 * @var string
	 */
	protected $name = 'authaccessstateenum';

	/**
	 * @var array
	 */
    protected $values = array(
    	\AccessControl\Repository\AuthAccessRepository::AUTH_ACCESS_PENDING_STATE,
    	\AccessControl\Repository\AuthAccessRepository::AUTH_ACCESS_ACTIVE_STATE
    );
}