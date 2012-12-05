<?php
namespace Mail\Service;
class MailService implements \Zend\ServiceManager\ServiceLocatorAwareInterface{
	const MEDIA_INTERNAL = 'internal';
	const MEDIA_MAIL = 'mail';
	
	const MAIL_DEFAULT = 'DEFAULT';
	const MAIL_SYSTEME = 'SYSTEME';
	const MAIL_NOREPLY = 'NOREPLY';
	
	const TEMPLATE_DEFAULT = 'mail/default';
	const TEMPLATE_EXCEPTION = 'mail/exception';
	
	/**
	 * @var \Zend\ServiceManager\ServiceLocatorInterface
	 */
	private $serviceLocator;

	/**
	 * @var array
	 */
	private $configuration;

	/**
	 * @var \Zend\View\Renderer\PhpRenderer
	 */
	private $renderer;

	/**
	 * @var \Zend\View\Model\ViewModel
	 */
	private $layout;

	/**
	 * Constructor
	 * @param array $aConfiguration
	 * @throws \Exception
	 */
	public function __construct(array $aConfiguration){
		if(!isset($aConfiguration['view_manager'],$this->configuration['view_manager']['template_map'],$aConfiguration['sender'])
		|| !is_array($aConfiguration['view_manager']['template_map']) || !is_array($aConfiguration['sender']))throw new \Exception('Configuration is invalid');
		$this->configuration = $aConfiguration;
	}
	
	/**
	 * @param \Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator
	 * @return \ZF2User\Service\UserService
	 */
	public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $oServiceLocator){
		$this->serviceLocator = $oServiceLocator;
		return $this;
	}
	
	/**
	 * @throws \Exception
	 * @return \Zend\ServiceManager\ServiceManager
	 */
	public function getServiceLocator(){
		if($this->serviceLocator instanceof \Zend\ServiceManager\ServiceLocatorInterface)return $this->serviceLocator;
		throw new \Exception('Service Locator is undefined');
	}

	/**
	 * Renvoie le renderer
	 * @throws \Exception
	 * @return \Zend\View\Renderer\PhpRenderer
	 */
	private function getRenderer(){
		if($this->renderer instanceof \Zend\View\Renderer\PhpRenderer)return $this->renderer;
		$this->renderer = new \Zend\View\Renderer\PhpRenderer();
		$this->renderer->setResolver(new \Zend\View\Resolver\TemplateMapResolver($this->configuration['view_manager']['template_map']));
		$this->layout = new \Zend\View\Model\ViewModel();
		$this->renderer->plugin('view_model')->setRoot($this->layout->setTemplate('mail/layout'));
		return $this->renderer;
	}

	/**
	 * Send email
	 * @param string|array $sRecipiants
	 * @param string $sSubject
	 * @param string $sBody
	 * @param string|null $sSender
	 * @param null|string|array $aAttachments
	 * @throws \Exception
	 */
	public function send($sRecipiants,$sSubject,$sBody,$sSender = self::MAIL_DEFAULT,$aAttachments = null,$sTemplate = self::TEMPLATE_DEFAULT){
		if(isset($this->configuration['sender'][$sSender]))$sSender = $this->configuration['sender'][$sSender];
		//Header view
    	$oHeaderView = new \Zend\View\Model\ViewModel(array('subject' => $sSubject));
    	$oHeaderView->setTemplate('mail/header');

    	//Body view
    	$oBodyView = new \Zend\View\Model\ViewModel(array('body'=> $sBody));
    	$oBodyView->setTemplate($sTemplate);

    	//Footer view
    	$oFooterView = new \Zend\View\Model\ViewModel();
    	$oFooterView->setTemplate('mail/footer');

    	$this->getRenderer()->layout()->objet = $sSubject;
    	$this->layout->addChild($oHeaderView, 'header')->addChild($oBodyView)->addChild($oFooterView, 'footer');

    	$this->view = new \Zend\View\View();
        $this->view->setEventManager(new \Zend\EventManager\EventManager());
        $this->view->setResponse(new \Zend\Stdlib\Response());
        $this->view->getEventManager()->attach(new \Zend\View\Strategy\PhpRendererStrategy($this->getRenderer()));
        $this->view->getEventManager()->attach(\Zend\View\ViewEvent::EVENT_RESPONSE,function($oEvent)use($sRecipiants,$sSubject,$sSender,$aAttachments){
		    $oMessage = new Message();
		    $oMessage->setFrom($sSender)
		    ->addTo($sRecipiants)
		    ->setSubject($sSubject)
		    ->setEncoding('UTF-8')
			->setBody($oEvent->getResult());
			if(isset($aAttachments))$oMessage->addAttachments($aAttachments);
		    $oTransport = new Sendmail();
		    $oTransport->send($oMessage);
        });
        $this->view->render($this->layout);
        return $this;
	}
}