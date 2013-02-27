<?php
namespace Database\Repository;
abstract class AbstractEntityRepository extends \Doctrine\ORM\EntityRepository{
    /**
     * @param \Database\Entity\AbstractEntity $oEntity
     * @return \Database\Entity\AbstractEntity
     */
    public function create(\Database\Entity\AbstractEntity $oEntity){
    	$this->_em->persist($oEntity->setEntityCreate(new \DateTime()));
        $this->_em->flush();
        return $oEntity;
    }

    /**
     * @param \Database\Entity\AbstractEntity $oEntity
     * @return \Database\Entity\AbstractEntity
     */
    public function update(\Database\Entity\AbstractEntity $oEntity){
    	$oEntity->setEntityUpdate(new \DateTime());
    	$this->_em->flush();
        return $oEntity;
    }

    /**
     * @param \Database\Entity\AbstractEntity $oEntity
     * @return \Database\Entity\AbstractEntity
     */
    public function remove(\Database\Entity\AbstractEntity $oEntity){
        $this->_em->remove($oEntity);
        $this->_em->flush($oEntity);
        return $oEntity;
    }
}