<?php

class Tyuiopas_View_Helper_FlashMessages extends Zend_View_Helper_Abstract {

    public function flashMessages() {
        $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
        $current_messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getCurrentMessages();
        Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->clearCurrentMessages();
        $output = '';
        if (!empty($messages) || !empty($current_messages)) {
            $output .= '<ul id="flash-messages">';
            foreach ($messages as $key => $message) {
                $output .= '<li class="' . $key . '">' . $message . '</li>';
            }
            foreach ($current_messages as $key => $message) {
                $output .= '<li class="' . $key . '">' . $message . '</li>';
            }
            $output .= '</ul>';
        }

        return $output;
    }

}

?>