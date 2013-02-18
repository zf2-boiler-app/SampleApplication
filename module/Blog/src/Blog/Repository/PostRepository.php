<?php
namespace Blog\Repository;
class PostRepository extends \Doctrine\ORM\EntityRepository implements \Blog\Mapper\PostMapperInterface{

	/**
	 * @param \Blog\Entity\PostEntity $oPost
	 * @return \Blog\Entity\PostEntity
	 */
    public function create(\Blog\Entity\PostEntity $oPost){
        $this->_em->persist($oPost);
        $this->_em->flush();
        return $oPost;
    }

    /**
     * Update the post
     * @param \Blog\Entity\PostEntity $post
     * @return \Blog\Entity\PostEntity
     */
    public function update(\Blog\Entity\PostEntity $oPost){
    	$this->_em->flush();
        return $post;
    }

    /**
     * @param \Blog\Entity\PostEntity $post
     */
    public function remove(\Blog\Entity\PostEntity $oPost){
        $this->_em->remove($post);
        $this->_em->flush($post);
    }
}