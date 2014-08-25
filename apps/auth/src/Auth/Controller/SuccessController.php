<?php
namespace Auth\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;


class SuccessController extends AbstractActionController
{
    public function successAction()
    {
        if (! $this->getServiceLocator()
            ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('auth');
        }
        $layout = $this->layout();
        $layout->setTemplate('layout/auth');
         return new ViewModel(array('messages'  => $this->flashmessenger()->getMessages()));
    }

}
