<?php
return array(
	//Entities
	'Database\Entity\AbstractEntity' => __DIR__.'/src/Database/Entity/AbstractEntity.php',

	//Factories
	'Database\Factory\AbstractEntityRepositoryFactory' => __DIR__.'/src/Database/Factory/AbstractEntityRepositoryFactory.php',

	//Repositories
	'Database\Repository\AbstractEntityRepository' => __DIR__.'/src/Database/Repository/AbstractEntityRepository.php',

	//RowGateway
	'Database\Db\RowGateway\AbstractRowGateway' => __DIR__.'/src/Database/Db/RowGateway/AbstractRowGateway.php',

	//RowGateway features
	'Database\Db\RowGateway\Feature\EventFeature' => __DIR__.'/src/Database/Db/RowGateway/Feature/EventFeature.php',
	'Database\Db\RowGateway\Feature\EventFeature\RowGatewayEvent' => __DIR__.'/src/Database/Db/RowGateway/Feature/EventFeature/RowGatewayEvent.php',

	//TableGateway
	'Database\Db\TableGateway\AbstractTableGateway' => __DIR__.'/src/Database/Db/TableGateway/AbstractTableGateway.php',
	'Database\Db\TableGateway\TableGateway' => __DIR__.'/src/Database/Db/TableGateway/TableGateway.php',

	//TableGateway features
	'Database\Db\TableGateway\Feature\EventFeature' => __DIR__.'/src/Database/Db/TableGateway/Feature/EventFeature.php',
	'Database\Db\TableGateway\Feature\EventFeature\TableGatewayEvent' => __DIR__.'/src/Database/Db/TableGateway/Feature/EventFeature/TableGatewayEvent.php',
	'Database\Db\TableGateway\Feature\MetadataFeature' => __DIR__.'/src/Database/Db/TableGateway/Feature/MetadataFeature.php',
	'Database\Db\TableGateway\Feature\RowGatewayFeature' => __DIR__.'/src/Database/Db/TableGateway/Feature/RowGatewayFeature.php',

	//Types
	'Database\Doctrine\DBAL\Types\AbstractEnumType' => __DIR__.'/src/Database/Doctrine/DBAL/Types/AbstractEnumType.php',
	'Database\Doctrine\DBAL\Types\EmailType' => __DIR__.'/src/Database/Doctrine/DBAL/Types/EmailType.php',
	'Database\Doctrine\DBAL\Types\Md5HashType' => __DIR__.'/src/Database/Doctrine/DBAL/Types/Md5HashType.php'
);