<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $oMessage = new \Messenger\Message();
    	$this->getServiceLocator()->get('MessengerService')->sendMessage(
    		$oMessage->setFrom(\Messenger\Message::SYSTEM_USER)
    		->setTo(\Messenger\Message::SYSTEM_USER)
    		->setSubject('Test subject')
    		->setBody('Test body'),
    		\Messenger\Service\MessengerService::MEDIA_EMAIL
    	);
    	return new ViewModel();
    }
}
