<?php
namespace Messenger;
class Message{
	const SYSTEM_USER = 'system';
	
	/**
	 * @var \ZF2User\Entity\UserEntity|string
	 */
	protected $from;
	
	/**
	 * @var array
	 */
	protected $to = array();
	
	/**
	 * Subject of the message
	 * @var string
	 */
	protected $subject;
	
	/**
	 * Content of the message
	 * @var string
	 */
	protected $body;
	
	/**
	 * Set From sender
	 * @param \ZF2User\Entity\UserEntity|string $oFrom
	 * @throws \Exception
	 * @return \Messenger\Message
	 */
	public function setFrom($oFrom = self::SYSTEM_USER){
		if($oFrom === self::SYSTEM_USER || $oFrom instanceof \ZF2User\Entity\UserEntity)$this->from = $oFrom;
		else throw new \Exception('From sender expects \Messenger\Message::SYSTEM_USER or \ZF2User\Entity\UserEntity');
		return $this;
	}

	/**
	 * Retrieve From sender
	 * @return \ZF2User\Entity\UserEntity|string
	 */
	public function getFrom(){
		return $this->from;
	}

	/**
	 * Set To recipients
	 * @param \ZF2User\Entity\UserEntity|string|array $aTo
	 * @throws \Exception
	 * @return \Messenger\Message
	 */
	public function setTo($aTo){
		$this->to = array();		
		return $this->addTo($aTo);
	}

	/**
	 * Add one or more recipients to the To recipients
	 * @param \ZF2User\Entity\UserEntity|string|array $aTo
	 * @throws \Exception
	 * @return \Messenger\Message
	 */
	public function addTo($aTo){
		if($aTo === self::SYSTEM_USER || $aTo instanceof \ZF2User\Entity\UserEntity)$aTo = array($aTo);
		elseif($aTo instanceof \Traversable)$aTo = \Zend\Stdlib\ArrayUtils::iteratorToArray($aTo);
		elseif(!is_array($aTo))throw new \Exception('To recipients expects \Messenger\Message::SYSTEM_USER, \ZF2User\Entity\UserEntity, array or Traversable object');
		$this->to = array_unique(array_merge(
			$this->to,
			array_filter($aTo,function($oTo){
				if($oTo === self::SYSTEM_USER || $oTo instanceof \ZF2User\Entity\UserEntity)return true;
				else throw new \Exception('Recipient expects \Messenger\Message::SYSTEM_USER or \ZF2User\Entity\UserEntity');
			})
		));		
		return $this;
	}

	/**
	 * Access the address list of the To header
	 * @return array
	 */
	public function getTo(){
		return $this->to;
	}
	
	/**
	 * Set the message subject value
	 * @param string $sSubject
	 * @throws \Exception
	 * @return \Messenger\Message
	 */
	public function setSubject($sSubject){
		if(!is_string($sSubject))throw new \Exception('Subject expects a string value');
		$this->subject = $sSubject;
		return $this;
	}

	/**
	 * Get the message subject header value
	 * @return null|string
	 */
	public function getSubject(){
		return $this->subject?:'';
	}

	/**
	 * Set the message body value
	 * @param string $sBody
	 * @throws \Exception
	 * @return \Messenger\Message
	 */
	public function setBody($sBody){
		if(!is_string($sBody))throw new \Exception('Body expects a string value');
		$this->body = $sBody;
		return $this;
	}

	/**
	 * Return the currently set message body
	 * @return string
	 */
	public function getBody(){
		return $this->body;
	}

	/**
	 * Serialize to string
	 * @return string
	 */
	public function toString(){
		return $this->getSubject().PHP_EOL.PHP_EOL.$this->getBody();
	}
}