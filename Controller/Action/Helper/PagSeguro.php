<?php

require_once "PagSeguroLibrary/PagSeguroLibrary.php";

class Tyuiopas_Controller_Action_Helper_PagSeguro extends Zend_Controller_Action_Helper_Abstract {

    private $request_storage = null;

    public function init() {
        $this->request_storage = array();
    }

    public function createPaymentRequest($refCode, $currency = null, $redirectURL = null) {

        $request = new PagSeguroPaymentRequest();

        $request->setReference($refCode);

        $psConfig = Zend_Registry::get('pagseguro');

        if (!isset($currency)) {
            $currency = $psConfig->default->currency;
        }

        if (!isset($redirectURL)) {
            $redirectURL = $psConfig->default->redirectURL;
        }

        if ($currency) {
            $request->setCurrency($currency);
        }

        if ($redirectURL) {
            $request->setRedirectURL($redirectURL);
        }

        $this->request_storage[$refCode] = $request;
        return $this->request_storage[$refCode];
    }

    public function getPaymentRequest($refCode) {
        if (isset($this->request_storage[$refCode])) {
            return $this->request_storage[$refCode];
        } else {
            throw new Exception('Invalid Payment Request');
        }
    }

    public function getPaymentUrl($refCode) {
        $config = Zend_Registry::get('pagseguro');
        try {

            $url = $this->getPaymentRequest($refCode)->register($this->getCredentials());
        } catch (PagSeguroServiceException $e) {
            throw $e;
        }
        return $url;
    }

    public function getCredentials() {
        $config = Zend_Registry::get('pagseguro');
        $credentials = new PagSeguroAccountCredentials(utf8_decode($config->email), utf8_decode($config->token));
        return $credentials;
    }

    public function getTransactionStatus($transactionCode) {

        $transaction = PagSeguroNotificationService::checkTransaction(
                        $this->getCredentials(), utf8_decode($transactionCode)
        );

        $status = $transaction->getStatus();
        return $status;
    }

}

?>
