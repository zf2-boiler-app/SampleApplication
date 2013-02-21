<?php
return array(
	'translation_file_patterns' => array(
		array(
			'type' => 'phparray',
			'base_dir' => __DIR__ . '/../languages',
			'pattern'  => '%s/Common.php'
		),
		array(
			'type' => 'phparray',
			'base_dir' => __DIR__ . '/../languages',
			'pattern'  => '%s/Validate.php',
			'text_domain' => 'validator'
		)
	)
);