<?php
namespace User\Repository;
class UserRepository extends \Database\Repository\AbstractRepository{
	/** User states */
	const USER_STATUS_PENDING = 'PENDING';
	const USER_STATUS_ACTIVE = 'ACTIVE';
}