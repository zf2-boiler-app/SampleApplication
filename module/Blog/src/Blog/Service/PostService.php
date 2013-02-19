<?php
namespace Blog\Service;
class PostService implements \Zend\ServiceManager\ServiceLocatorAwareInterface{
	use \Zend\ServiceManager\ServiceLocatorAwareTrait;

	public function getPosts($iCurrentPage = 1){
		$oDoctrinePaginator = new \DoctrineORMModule\Paginator\Adapter\DoctrinePaginator(
			new \Doctrine\ORM\Tools\Pagination\Paginator($this->getServiceLocator()->get('Blog\Repository\PostRepository')->createQueryBuilder('posts'))
		);
		$oPaginator = new \Zend\Paginator\Paginator($oDoctrinePaginator);
		$oPaginator->setDefaultItemCountPerPage(10);
		$oPaginator->setCurrentPageNumber($iCurrentPage);
		return $oPaginator;
	}

	/**
	 * @param string $sPostTitle
	 * @param string $sPostContent
	 * @return \Blog\Service\PostService
	 */
	public function createPost($sPostTitle,$sPostContent){
		$oPost = new \Blog\Entity\PostEntity();
		$this->getServiceLocator()->get('Blog\Repository\PostRepository')->create($oPost
			->setPostTitle($sPostTitle)
			->setPostContent($sPostContent)
			->setPostAuthor($this->getServiceLocator()->get('AccessControlService')->getLoggedUser())
		);
		return $this;
	}

	public function readPost($sPostId){}

	public function updatePost(array $aPostData){}

	public function deletePost($sPostId){}
}