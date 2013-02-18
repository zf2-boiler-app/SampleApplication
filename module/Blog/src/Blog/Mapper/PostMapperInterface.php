<?php
namespace Blog\Mapper;
interface PostMapperInterface extends \Doctrine\Common\Persistence\ObjectRepository{
    /**
     * @param \Blog\Entity\PostEntity $oPost
     * @return \Blog\Entity\PostEntity
     */
    public function create(\Blog\Entity\PostEntity $oPost);

    /**
     * @param \Blog\Entity\PostEntity $oPost
     * @return \Blog\Entity\PostEntity
     */
    public function update(\Blog\Entity\PostEntity $oPost);

    /**
     * @param \Blog\Entity\PostEntity $oPost
     */
    public function remove(\Blog\Entity\PostEntity $oPost);
}