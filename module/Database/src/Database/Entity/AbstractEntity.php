<?php
namespace Database\Entity;
abstract class AbstractEntity{

	/**
	 * @var \DateTime
	 * @\Doctrine\ORM\Mapping\Column(type="datetime")
	 */
	protected $entity_create;

	/**
	 * @var \DateTime
	 * @\Doctrine\ORM\Mapping\Column(type="datetime")
	 */
	protected $entity_update;

	/**
	 * @param \DateTime $oDate
	 * @return \Database\Entity\AbstractEntity
	 */
	public function setEntityCreate(\DateTime $oDate){
		$this->entity_create = $oDate;
		return $this;
	}

	/**
	 * @return DateTime
	 */
	public function getEntityCreate(){
		return $this->entity_create;
	}

	/**
	 * @param \DateTime $oDate
	 * @return \Database\Entity\AbstractEntity
	 */
	public function setEntityUpdate(\DateTime $oDate){
		$this->entity_update = $oDate;
		return $this;
	}

	/**
	 * @return DateTime
	 */
	public function getEntityUpdate(){
		return $this->entity_update;
	}
}