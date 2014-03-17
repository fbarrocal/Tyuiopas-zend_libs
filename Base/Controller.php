<?php

class Tyuiopas_Base_Controller extends Zend_Controller_Action {

    var $logger = null;
    var $translator = null;

    public function init() {
        $this->logger = Zend_Registry::get('logger');
    }

    public function isLoggedIn() {
        return Zend_Auth::getInstance()->hasIdentity();
    }

    public function log($message, $level = Zend_Log::INFO) {
        if (!isset($this->logger) && Zend_Registry::isRegistered('logger')) {
            $this->logger = Zend_Registry::get('logger');
        }
        $this->logger->log($message, $level);
    }

    public function flashMessage($message, $level = null) {
        if (isset($level)) {
            $message_array = array($level => $message);
            $this->_helper->flashMessenger($message_array);
        } else {
            $this->_helper->flashMessenger($message);
        }
    }

//    public function forward($action, $controller = null, $module = null, array $params = null) {
//        parent::_forward($action, $controller, $module, $params);
//    }
//    
//    public function redirect($url, array $options = array()) {
//        $this->log("Trying to translate URL: " . $url);
//        if (!isset($this->translator) && Zend_Registry::isRegistered('Zend_Translate')) {
//            $this->translator = Zend_Registry::get('Zend_Translate');
//            $url_parts = split('/', $url);
//            if (strpos($url, '/') === 0) {
//                $translated_url = '/';
//            } else {
//                $translated_url = "";
//            }
//            foreach ($url_parts as $key => $part) {
//                $translated_url .= $this->translator->translate($part) . '/';
//            }
//            $this->log("Translated URL from " . $url . " to " . $translated_url);
//            //$url = $translated_url;
//        }
//        parent::_redirect($url, $options);
//    }
}

?>
