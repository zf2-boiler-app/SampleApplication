<?php
namespace Blog\Repository;
class PostRepository extends \Doctrine\ORM\EntityRepository implements \Blog\Mapper\PostMapperInterface{

	/**
	 * @param \Blog\Entity\Post $oPost
	 * @return \Blog\Entity\Post
	 */
    public function create(\Blog\Entity\Post $oPost){
        $this->_em->persist($oPost);
        $this->_em->flush();
        return $oPost;
    }

    /**
     * Update the post
     * @param  Post $post
     * @return Post
     */
    public function update(\Blog\Entity\Post $oPost){
    	$this->_em->flush();
        return $post;
    }

    /**
     * @param  Post $post
     * @return void
     */
    public function remove(\Blog\Entity\Post $oPost){
        $this->_em->remove($post);
        $this->_em->flush($post);
    }
}
