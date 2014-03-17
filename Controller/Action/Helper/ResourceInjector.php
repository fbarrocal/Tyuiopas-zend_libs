<?php

class Tyuiopas_Controller_Action_Helper_ResourceInjector extends Zend_Controller_Action_Helper_Abstract {

    protected $_resources = array(
        'logger',
        'settings'
    );

    public function preDispatch() {
        $bootstrap = $this->getBootstrap();
        $controller = $this->getActionController();

        $controller->_redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');

        foreach ($this->_resources as $name) {
            if ($bootstrap->hasResource($name)) {
                $controller->$name = $bootstrap->getResource($name);
                $controller->view->$name = $bootstrap->getResource($name);
            }
            //$bootstrap->getResource('logger')->log("Initializing $name resource", Zend_Log::DEBUG);
        }

        if (isset($controller->resources) && is_array($controller->resources)) {
            foreach ($controller->resources as $name) {
                if (!$bootstrap->hasResource($name)) {
                    throw new DomainException("Unable to find resource by name '$name'");
                }
                $controller->$name = $bootstrap->getResource($name);
                $controller->view->$name = $bootstrap->getResource($name);
                //$bootstrap->getResource('logger')->log("Initializing $name controller resource", Zend_Log::DEBUG);
            }
        }

        $active = $controller->view->navigation()->findActive($controller->view->navigation()->getContainer());

        if (isset($active) && isset($active['page'])) {
            $controller->view->active_page = $active['page'];
        }
    }

    public function getBootstrap() {
        return $this->getFrontController()->getParam('bootstrap');
    }

}

?>
