<?php
/**
 * Created by JetBrains PhpStorm.
 * User: admin
 * Date: 14.11.13
 * Time: 19:40
 * To change this template use File | Settings | File Templates.
 */
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Auth\Form\LoginForm;
use Auth\Form\LoginFilter;
use Zend\Session\Container;


class AuthController extends AbstractActionController{

    protected $authService;
    protected $storage;

    public function setAuthService($oAuthServices){
        $this->authService = $oAuthServices;
    }
    public function getAuthService(){


        if($this->authService == null){
            $sm = $this->getServiceLocator();
            $this->authService = $sm->get('AuthService');
        }
        return $this->authService;
    }

    public function getSessionStorage(){


        if (! $this->storage) {
            $this->storage = $this->getServiceLocator()->get('Auth\Model\MyAuthStorage');
        }

        return $this->storage;
    }

    public function loginAction(){
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('success');
        }

        $layout = $this->layout();
        $layout->setTemplate('layout/auth');

        $oLoginForm = new LoginForm();
        $view = new ViewModel(array('form'=>$oLoginForm, 'messages'  => $this->flashmessenger()->getMessages()));
        return $view;
    }

    public function authenticateAction(){
        $oLoginForm = new LoginForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $oLoginForm->setData($request->getPost());
            $oLoginFilter = new LoginFilter();
            $oLoginForm->setInputFilter($oLoginFilter->getInputFilter());
            if ($oLoginForm->isValid()) {
                $this->getAuthService()->getAdapter()
                    ->setIdentity($request->getPost('login'))
                    ->setCredential( md5($request->getPost('password')));
                $result = $this->getAuthService()->authenticate();
                foreach($result->getMessages() as $message)
                {
                    //save message temporary into flashmessenger
                    $this->flashmessenger()->addMessage($message);
                }

                if($result->isValid()){
                    $aUser = $this->getAuthService()->getAdapter()->getResultRowObject();
                    $this->getAuthService()->getStorage()->write($aUser);

                    return $this->redirect()->toRoute('success');
                }

            }else{
                $this->flashmessenger()->addMessage($oLoginForm->getMessages());
            }
        }
        return $this->redirect()->toRoute('auth');

    }

    public function logoutAction(){
        $this->getAuthService()->clearIdentity();
        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute('auth');
    }

    public function cookieAction(){
        $this->getAuthService()->clearIdentity();
        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute('auth');
    }
}

