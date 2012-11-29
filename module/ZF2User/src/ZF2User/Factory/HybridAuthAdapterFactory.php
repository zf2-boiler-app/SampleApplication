<?php
namespace ZF2User\Factory;
class HybridAuthAdapterFactory implements \Zend\ServiceManager\FactoryInterface{
	public function createService(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$aConfiguration = $oServiceLocator->get('Config');
		if(!isset($aConfiguration['hybrid_auth']) || !is_array($aConfiguration['hybrid_auth']))throw new \Exception('HybridAuth\'s config is undefined');
		$aConfiguration = $aConfiguration['hybrid_auth'];
		//Rewrite base url
		$aConfiguration['base_url'] = $oServiceLocator->get('Router')->assemble(array(),array('name' => $aConfiguration['base_url'],'force_canonical' => true));

		/* @var $oSocialService \Application\View\Helper\Social */
		$oSocialService = $oServiceLocator->get('social');
		foreach($aConfiguration['providers'] as $sProvider => $aInfosProvider){
			$aProviderConfig = $oSocialService->getServiceConfig(strtolower($sProvider));
			if(isset($aConfiguration['providers'][$sProvider]['keys']['id']))$aConfiguration['providers'][$sProvider]['keys']['id'] = $aProviderConfig['id'];
			elseif(isset($aConfiguration['providers'][$sProvider]['keys']['key']))$aConfiguration['providers'][$sProvider]['keys']['key'] = $aProviderConfig['id'];
			$aConfiguration['providers'][$sProvider]['keys']['secret'] = $aProviderConfig['key'];
		}
		return new \Hybrid_Auth($aConfiguration);
    }
}