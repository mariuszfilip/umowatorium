<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use My\Dhtmlx\Models\FormElements;
use Application\Model\UserTable;

use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\EventManager\EventInterface;
class Module
{
    protected $whitelist = array('auth');

    public function onBootstrap(MvcEvent $e)
    {

      /*  $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $app = $e->getApplication();
        $em  = $app->getEventManager();
        $sm  = $app->getServiceManager();
        $auth = $sm->get('AuthService');
        $list = $this->whitelist;
        $oUserTable = $sm->get('UserTable');
        $em->attach(MvcEvent::EVENT_ROUTE, function($e) use ($list, $auth , $oUserTable) {

            $match = $e->getRouteMatch();
            // No route match, this is a 404
            if (!$match instanceof RouteMatch) {
                //return;
            }

            // Route is whitelisted
            $name = $match->getMatchedRouteName();

            if (in_array($name, $list)) {
                return;
            }
            // User is authenticated
            if ($auth->hasIdentity()) {
                $id =  (int)$auth->getStorage()->read()->id;
                $oUser = $oUserTable->getUser($id);
                $e->getViewModel()->setVariable('username', $oUser->getName());
                return;
            }


            $router   = $e->getRouter();
            $url      = $router->assemble(array(), array(
                'name' => 'auth'
            ));

            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);

            return $response;
        }, -100);*/
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'FormsElementTable' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table     = new FormElements($dbAdapter);
                    return $table;
                },
                'UserTable' =>  function($sm) {
                    $dbAdapter= $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new UserTable($dbAdapter);
                    return $table;
                },

            ),
        );
    }

}
