<?php
namespace Mail;
use \Zend\Mail\Message as OriginalMessage;
class Message extends OriginalMessage{

	/**
	 * @var array
	 */
	protected $attachments = array();

	/**
	 * Add files path to attachments
	 * @param string|array $aFilesPath
	 * @throws \Exception
	 */
	public function addAttachments(array $aFilesPath){
		if(empty($aFilesPath))throw new \Exception('Files path is empty');
		foreach($aFilesPath as $sFilePath){
			if(!file_exists($sFilePath))throw new \Exception('Attachment file not found');
			$this->attachments[] = $sFilePath;
		}
		return $this;
	}

	/**
	 * Retreive attachments
	 * @return array
	 */
	public function getAttachments(){
		return $this->attachments;
	}

	/**
	 * @return boolean
	 */
	public function hasAttachment(){
		return !empty($this->attachments);
	}
}
