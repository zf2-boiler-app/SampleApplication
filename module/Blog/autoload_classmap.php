<?php
return array(
	//Controllers
	'Blog\Controller\IndexController' => __DIR__.'/src/Blog/Controller/IndexController.php',
	'Blog\Controller\PostController' => __DIR__.'/src/Blog/Controller/PostController.php',

	//Entities
	'Blog\Entity\PostEntity' => __DIR__.'/src/Blog/Entity/PostEntity.php',

	//Factories
	'Blog\Factory\PostFormFactory' => __DIR__.'/src/Blog/Factory/PostFormFactory.php',

	//Forms
	'Blog\Form\PostForm' => __DIR__.'/src/Blog/Form/PostForm.php',

	//InputFilters
	'Blog\InputFilter\PostInputFilter' => __DIR__.'/src/Blog/InputFilter/PostInputFilter.php',

	//Repositories
	'Blog\Repository\PostRepository' => __DIR__.'/src/Blog/Repository/PostRepository.php',

	//Services
	'Blog\Service\PostService' => __DIR__.'/src/Blog/Service/PostService.php'
);