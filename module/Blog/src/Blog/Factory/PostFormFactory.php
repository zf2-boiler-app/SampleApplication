<?php
namespace Blog\Factory;
class PostFormFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$oForm = new \Blog\Form\PostForm();
		return $oForm->prepare();
    }
}