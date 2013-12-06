<?php
/**
 * Contus Support Interactive.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file GCLONE-LICENSE.txt.
 * The CONTUS GCLONE License is available at this URL:
 * http://www.groupclone.net/GCLONE-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Contus Support does not guarantee correct work of this package
 * on any other Magento edition except Magento COMMUNITY edition.
 * =================================================================
 */

require_once ('cc_class.php');

class Gclone_Constantcontact_Model_Constantcontact_Constantcontact extends Varien_Object {
    public function _construct(){
        
       
    }

        
    public function subscribe($email, $city) {
        $session = Mage::getSingleton('core/session');
        header('Content-Type: text/html; charset=UTF-8');
        
        //when the object is getting initialized, the login string must be created as API_KEY%LOGIN:PASSWORD
        $login = Mage::getStoreConfig('constantcontact/general/username'); //Username for your account
        $password = Mage::getStoreConfig('constantcontact/general/password'); //Password for your account
        $apikey = Mage::getStoreConfig('constantcontact/general/apikey'); // API Key for your account.
        $authstr = $apikey . '%' . $login . ':' . $password;
        $apiPath = 'https://api.constantcontact.com/ws/customers/'.$login;
        //Initialize a object for cc_contact class
        $ccContactOBJ = new CC_Contact($authstr, $apiPath);

        $allMySubscribers = $ccContactOBJ->getSubscribers($email, '');

        foreach ($allMySubscribers['items'] as $key => $item) {
            $postId = $item['id'];
        }
        $mailList = array();
        $mailList[0] = Mage::getStoreConfig('constantcontact/general/listid');

        $postFields = array();
        $postFields["email_address"] = $email;

        $postFields["city_name"] = $city;

        $postFields["lists"] = $mailList;


        if (count($allMySubscribers) > 2) {

            $contactXML = $ccContactOBJ->createContactXML($postId, $postFields);

            if (!$ccContactOBJ->editSubscriber($postId, $contactXML)) {
                $error = true;
            } else {
                $error = false;
            }
        } else {
            $contactXML = $ccContactOBJ->createContactXML(null, $postFields);

            if (!$ccContactOBJ->addSubscriber($contactXML)) {
                $error = true;
            } else {
                $error = false;
                $_POST = array();
            }
        }

        if (isset($error)) {

            if ($error === true) {
                $class = "error";
                $message = $ccContactOBJ->lastError;
                $session->addError($this->__('There was a problem with the subscription in Constant Contact'));
            } else {
                $class = "success";
                $message = "Contact " . htmlspecialchars($postFields["email_address"], ENT_QUOTES, 'UTF-8') . " Added.";
            }

            echo '<div class="' . $class . '">';
            echo $message;
            echo '</div>';
        }
        //return $message;
        //$this->_redirect('*/*/');
    }

    public function unsubscribe($email) {
        //when the object is getting initialized, the login string must be created as API_KEY%LOGIN:PASSWORD
        $login = Mage::getStoreConfig('constantcontact/general/username'); //Username for your account
        $password = Mage::getStoreConfig('constantcontact/general/password'); //Password for your account
        $apikey = Mage::getStoreConfig('constantcontact/general/apikey'); // API Key for your account.
        $authstr = $apikey . '%' . $login . ':' . $password;
        $apiPath = 'https://api.constantcontact.com/ws/customers/'.$login;
        //Initialize a object for cc_contact class
        $ccContactOBJ = new CC_Contact($authstr, $apiPath);
        if (empty($email)) {
            return;
        }

        $ccContactOBJ->removeSubscriber($email);
           
    }

    public function batchSubscribe($items) {
        $session = Mage::getSingleton('core/session');
        header('Content-Type: text/html; charset=UTF-8');
        //when the object is getting initialized, the login string must be created as API_KEY%LOGIN:PASSWORD
        $login = Mage::getStoreConfig('constantcontact/general/username'); //Username for your account
        $password = Mage::getStoreConfig('constantcontact/general/password'); //Password for your account
        $apikey = Mage::getStoreConfig('constantcontact/general/apikey'); // API Key for your account.
        $authstr = $apikey . '%' . $login . ':' . $password;
        $apiPath = 'https://api.constantcontact.com/ws/customers/'.$login;
        //Initialize a object for cc_contact class
        $ccContactOBJ = new CC_Contact($authstr, $apiPath);

        foreach ($items as $key1 => $get) {
            $email = $get['subscriber_email'];
            $city = $get['subscriber_city'];

            $allMySubscribers = $ccContactOBJ->getSubscribers($email, '');
            $mailList = array();
            $mailList[0] = Mage::getStoreConfig('constantcontact/general/listid');

            $postFields = array();
            $postFields["email_address"] = $email;

            $postFields["city_name"] = $city;

            $postFields["lists"] = $mailList;

            if (count($allMySubscribers) > 2) {
                foreach ($allMySubscribers['items'] as $key => $item) {
                    $postId = $item['id'];
                }
                $contactXML = $ccContactOBJ->createContactXML($postId, $postFields);

                if (!$ccContactOBJ->editSubscriber($postId, $contactXML)) {
                    $error = true;
                } else {
                    $error = false;
                }
            } else {
                $contactXML = $ccContactOBJ->createContactXML(null, $postFields);

                if (!$ccContactOBJ->addSubscriber($contactXML)) {
                    $error = true;
                } else {
                    $error = false;
                    $_POST = array();
                }
            }

            if (isset($error)) {

                if ($error === true) {
                    $class = "error";
                    $message = $ccContactOBJ->lastError;
                    $session->addError($this->__('There was a problem with the subscription in Constant Contact'));
                } else {
                    $class = "success";
                    $message = "Contact " . htmlspecialchars($postFields["email_address"], ENT_QUOTES, 'UTF-8') . " Added.";
                }

                echo '<div class="' . $class . '">';
                echo $message;
                echo '</div>';
            }
        }
    }

}

?>
