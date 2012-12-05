<?php
namespace Mail\Transport;
use \Zend\Mail\Transport\Sendmail as OriginalSendmail;
class Sendmail extends OriginalSendmail{

	/**
	 * @var array
	 */
	protected $attachments = array();

	/**
	 * Prepare body
	 * @param \Zend\Mail\Message $oMessage
	 */
	protected function prepareBody(\Zend\Mail\Message $oMessage){
		$oBodyPart = new \Zend\Mime\Part(preg_replace_callback('/src="([^"]+)"/',array($this,'processImageSrc'), $oMessage->getBodyText()));
		$oBodyPart->type = \Zend\Mime\Mime::TYPE_HTML;
		$oBodyPart->charset = 'UTF-8';

		$this->attachments[] = $oBodyPart;

	    $oBody = new \Zend\Mime\Message();
		$oBody->setParts($this->attachments);
	    return $oMessage->setBody($oBody)->getBodyText();
	}

	/**
	 * Add image to attachments
	 * @param array $aMatches
	 * @throws \Exception
	 * @return string
	 */
	protected function processImageSrc(array $aMatches){
		if(empty($aMatches[1]))throw new \Exception('Image matches is invalid');
		$oAttachment = $this->addAttachement($aMatches[1],\Zend\Mime\Mime::DISPOSITION_INLINE);
		return 'src="cid:'.$oAttachment->id.'"';
	}

	/**
	 * Add file to attachments
	 * @param string $sFilePath
	 * @throws \Exception
	 * @return \Zend\Mime\Part
	 */
	protected function addAttachment($sFilePath,$sDisposition = \Zend\Mime\Mime::DISPOSITION_ATTACHMENT){
		if(($sFileContent = file_get_contents($sFilePath)) === false)throw new \Exception('Attachment file not found');

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
	 * Send mail
	 * @param \Zend\Mail\Message $oMessage
	 */
	public function send(\Zend\Mail\Message $oMessage){
        $this->prepareAttachements($oMessage);
		parent::send($oMessage);
	}

	/**
	 * Add message attachments
	 * @param \Zend\Mail\Message $oMessage
	 * @return \Mail\Transport\Sendmail
	 */
	protected function prepareAttachements(\Zend\Mail\Message $oMessage){
		if($oMessage->hasAttachment())foreach($oMessage->getAttachments() as $sFilePath)$this->addAttachment($sFilePath);
		return $this;
	}
}