<?php
namespace Messenger\Mail;
use \Zend\Mail\Message as OriginalMessage;
class Message extends OriginalMessage{

	/**
	 * @var array
	 */
	protected $attachments = array();

	/**
	 * @param string $sFilePath
	 * @throws \Exception
	 */
	public function addAttachment($sFilePath){
		if(empty($sFilePath) || !file_exists($sFilePath))throw new \Exception('Attachment file not found : '.$sFilePath);
		$this->attachments[] = $sFilePath;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getAttachments(){
		return $this->attachments;
	}

	/**
	 * @return boolean
	 */
	public function hasAttachments(){
		return !!$this->attachments;
	}
}
