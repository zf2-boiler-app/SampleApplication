<?php
namespace Blog\Mapper;
interface PostMapperInterface extends \Doctrine\Common\Persistence\ObjectRepository{
    /**
     * @param \Blog\Entity\Post $oPost
     * @return \Blog\Entity\Post
     */
    public function create(\Blog\Entity\Post $oPost);

    /**
     * @param \Blog\Entity\PostPost $oPost
     * @return \Blog\Entity\Post
     */
    public function update(\Blog\Entity\Post $oPost);

    /**
     * @param\Blog\Entity\Post $oPost
     * @return void
     */
    public function remove(\Blog\Entity\Post $oPost);
}
