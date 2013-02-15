<?php
namespace Blog\Service;
class PostService implements \Zend\ServiceManager\ServiceLocatorAwareInterface{
	use \Zend\ServiceManager\ServiceLocatorAwareTrait;

	/**
	 * @param string $sPostTitle
	 * @param string $sPostContent
	 * @return \Blog\Service\PostService
	 */
	public function createPost($sPostTitle,$sPostContent){
		$oPost = new \Blog\Entity\PostEntity();
		$this->getServiceLocator()->get('Blog\Mapper\PostMapperInterface')->create($oPost
			->setPostTitle($sPostTitle)
			->setPostContent($sPostContent)
		);
		return $this;
	}

	public function readPost($sPostId){}

	public function updatePost(array $aPostData){}

	public function deletePost($sPostId){}
}