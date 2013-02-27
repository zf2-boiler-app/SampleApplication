<?php
namespace Messenger\Mail\Transport;
use \Zend\Mail\Transport\Smtp as OriginalSmtp;
class Smtp extends OriginalSmtp{

	/**
	 * @var array
	 */
	protected $attachments = array();

	/**
	 * Prepare message body
	 * @param \Messenger\Mail\Message $oMessage
	 * @return string
	 */
	protected function prepareMessage(\Messenger\Mail\Message $oMessage){
		if($oMessage->hasattachments())foreach($oMessage->getAttachments() as $sAttachmentPath){
			$this->addAttachment($sAttachmentPath);
		}

		$oBodyPart = new \Zend\Mime\Part(preg_replace_callback('/src="([^"]+)"/',array($this,'processImageSrc'),$oMessage->getBodyText()));
		$oBodyPart->type = \Zend\Mime\Mime::TYPE_HTML;
		$oBody = new \Zend\Mime\Message();
		$oBody->setParts(array_merge(array($oBodyPart),$this->attachments));
		return $oMessage->setBody($oBody)->setEncoding('UTF-8');
	}

	/**
	 * Add image to attachments
	 * @param array $aMatches
	 * @throws \Exception
	 * @return string
	 */
	protected function processImageSrc(array $aMatches){
		if(empty($aMatches[1]))throw new \Exception('Image "src" match is empty: '.print_r($aMatches));
		$oAttachment = $this->addAttachment(urldecode($aMatches[1]),\Zend\Mime\Mime::DISPOSITION_INLINE);
		return 'src="cid:'.$oAttachment->id.'"';
	}

	/**
	 * @param string $sFilePath
	 * @throws \Exception
	 * @return \Zend\Mime\Part
	 */
	protected function addAttachment($sFilePath,$sDisposition = \Zend\Mime\Mime::DISPOSITION_ATTACHMENT){
		if(!file_exists($sFilePath) || ($sFileContent = file_get_contents($sFilePath)) === false)throw new \Exception('Attachment file not found : '.$sFilePath);

		$oAttachment = new \Zend\Mime\Part($sFileContent);
		$oFInfo = new \finfo(FILEINFO_MIME_TYPE);
		$oAttachment->type = $oFInfo->buffer($sFileContent);
		$oAttachment->description = $oAttachment->filename = pathinfo($sFilePath,PATHINFO_FILENAME);
		$oAttachment->id = md5(uniqid());
		$oAttachment->encoding = \Zend\Mime\Mime::ENCODING_BASE64;
		$oAttachment->disposition = $sDisposition;
		$this->attachments[] = $oAttachment;
		return $oAttachment;
	}

	/**
	 * Send email
	 * @param \Zend\Mail\Message $message
	 */
	public function send(\Zend\Mail\Message $oMessage){
		parent::send($this->prepareMessage($oMessage));
	}
}