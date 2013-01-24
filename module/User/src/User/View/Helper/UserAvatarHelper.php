<?php
namespace User\View\Helper;
class UserAvatarHelper extends \Zend\View\Helper\AbstractHelper{
	/**
	 * @var string
	 */
	protected $avatarsPath;

	/**
	 * @param string $sAvatarsPath
	 * @return \Application\View\Helper\UserAvatarHelper
	 */
	public function setAvatarsPath($sAvatarsPath){
		if(!is_string($sAvatarsPath) || !is_dir($sAvatarsPath))throw new \Exception('Avatars path is not a valid directory path : '.$sAvatarsPath);
		$this->avatarsPath = realpath($sAvatarsPath);
		return $this;
	}

	/**
	 * @throws \Exception
	 * @return string
	 */
	public function getAvatarsPath(){
		if(!$this->avatarsPath)throw new \Exception('Avatars path is undefined');
		return $this->avatarsPath;
	}



	/**
	 * @param \User\Entity\UserEntity $oUser
	 * @return string
	 */
	public function __invoke(\User\Entity\UserEntity $oUser){
		if(!file_exists($sAvatarPath = $this->getAvatarsPath().DIRECTORY_SEPARATOR.$oUser->getUserId().'-avatar.png'))$sAvatarPath = $this->getAvatarsPath().DIRECTORY_SEPARATOR.'default-avatar.png';
		return base64_encode(file_get_contents($sAvatarPath));
	}
}