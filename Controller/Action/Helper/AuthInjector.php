<?php

class Tyuiopas_Controller_Action_Helper_AuthInjector extends Zend_Controller_Action_Helper_Abstract {


    public function preDispatch() {
        $bootstrap = $this->getBootstrap();
        $controller = $this->getActionController();

        $controller->_redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');

        $identity = Zend_Auth::getInstance()->getIdentity();
        if ($identity) {
            $controller->identity = $identity;
            $controller->view->identity = $identity;
        }

    }

    public function getBootstrap() {
        return $this->getFrontController()->getParam('bootstrap');
    }

}

?>
