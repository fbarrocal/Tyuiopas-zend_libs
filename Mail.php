<?php

/**
 * A template based email system
 * Supports the sending of multipart txt/html emails based on templates
 *
 * @author Fernando Barrocal
 */
class Tyuiopas_Mail {

    /**
     * Variable registry for template values
     */
    protected $templateVariables = array();

    /**
     * Template name
     */
    protected $templateName;

    /**
     * Zend_Mail instance
     */
    protected $zendMail;

    /**
     * Email recipient
     */
    protected $recipient;

    /**
     * __construct
     *
     * Set default options
     *
     */
    public function __construct($template = null) {
        $this->zendMail = new Zend_Mail('UTF-8');
        if ($template !== null)
            $this->setTemplate($template);
    }

    /**
     * Set variables for use in the templates
     *
     * Magic function stores the value put in any variable in this class for
     * use later when creating the template
     *
     * @param string $name  The name of the variable to be stored
     * @param mixed  $value The value of the variable
     */
    public function __set($name, $value) {
        $this->templateVariables[$name] = $value;
    }

    /**
     * Set the template file to use
     *
     * @param string $filename Template filename
     */
    public function setTemplate($filename) {
        $this->templateName = $filename;
    }

    /**
     * Set the recipient address for the email message
     *
     * @param string $email Email address
     */
    public function setRecipient($email) {
        $this->recipient = $email;
    }

    /**
     * Send the constructed email
     *
     * @todo Add from name
     */
    public function send() {
        /*
         * Get data from config
         * - From address
         * - Directory for template files
         */
        $config = Zend_Registry::get('config');
        $templateDir = $config->email->template->dir;
        $fromName = utf8_decode($config->email->from);
        $fromAddr = $config->email->from_address;
        if (isset($config->email->vars)) {
            $templateVars = $config->email->vars->toArray();
        } else {
            $templateVars = array();
        }

        foreach ($templateVars as $key => $value) {
            //If a variable is present in config which has not been set
            //add it to the list
            if (!array_key_exists($key, $this->templateVariables)) {
                $this->{$key} = $value;
            }
        }

        //Build template
        //Check that template file exists before using
        $viewConfig = array('basePath' => $templateDir);
        $subjectView = new Zend_View($viewConfig);
        foreach ($this->templateVariables as $key => $value) {
            $subjectView->{$key} = $value;
        }
        try {
            $subject = $subjectView->render($this->templateName . '.subj');
        } catch (Zend_View_Exception $e) {
            $subject = false;
        }

        $textView = new Zend_View($viewConfig);
        foreach ($this->templateVariables as $key => $value) {
            $textView->{$key} = $value;
        }
        try {
            $text = $textView->render($this->templateName . '.txt');
        } catch (Zend_View_Exception $e) {
            $text = false;
        }

        $htmlView = new Zend_View($viewConfig);
        foreach ($this->templateVariables as $key => $value) {
            $htmlView->{$key} = $value;
        }
        try {
            $html = $htmlView->render($this->templateName . '.html');
        } catch (Zend_View_Exception $e) {
            $html = false;
        }

        //Pass variables to Zend_Mail
        $mail = new Zend_Mail();

        $mail->setFrom($fromAddr, $fromName);

        $mail->addTo($this->recipient);

        $mail->setSubject(utf8_decode($subject));

        $mail->setBodyText(utf8_decode($text));
        if ($html !== false) {
            $mail->setBodyHtml(utf8_decode($html));
        }

        $mail->send();
    }

}
