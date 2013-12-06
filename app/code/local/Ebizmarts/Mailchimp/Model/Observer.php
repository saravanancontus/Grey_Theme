<?php

class Ebizmarts_Mailchimp_Model_Observer extends Mage_Core_Model_Abstract {

    public function subscribeObserver($observer) {

        $params = (Mage::app()->getRequest()->getParams()) ? Mage::app()->getRequest()->getParams() : array();

        if (isset($params['email'])) {
            $params['is_general'] = true;
            Mage::helper('mailchimp')->preFilter($params['email'], $params);
        }

        return $this;
    }

    public function customerObserver($observer) {

        $params = (Mage::app()->getRequest()->getParams()) ? Mage::app()->getRequest()->getParams() : array();

        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $email = $customer->getEmail();

        if (isset($email)) {
            Mage::helper('mailchimp')->preFilter($email, $params);
        }
        return $this;
    }

    public function registerObserver($observer) {

        $params = (Mage::app()->getRequest()->getParams()) ? Mage::app()->getRequest()->getParams() : array();

        if (isset($params['is_subscribed']) || isset($params['subscribe_newsletter']) ||
                (bool) Mage::helper('mailchimp')->getSubscribeConfig('forece_checkout', Mage::app()->getStore()->getStoreId())) {

            $email = (isset($params['billing']['email'])) ? $params['billing']['email'] : Mage::getSingleton('customer/session')->getCustomer()->getEmail();
            $params['is_general'] = (!isset($params['is_general'])) ? (int) 1 : $params['is_general'];

            if ($email) {
                Mage::getModel('newsletter/subscriber')->subscribe($email, $params['city'], true);
                Mage::helper('mailchimp')->preFilter($email, $params);
            }
        }
        return $this;
    }

    public function updateNewObserver($observer) {

        $params = (Mage::app()->getRequest()->getParams()) ? Mage::app()->getRequest()->getParams() : array();
        $cusSession = Mage::getSingleton('customer/session')->getCustomer();

        $customer = ($cusSession->getEmail()) ? $cusSession : $observer->getCustomer();
        if (isset($params['email'])) {
            if ($params['email'] != $customer->getEmail()) {
                $email = $params['email'];
                $params['oldEmail'] = $customer->getEmail();
            } else {
                $email = ($customer->getEmail()) ? $customer->getEmail() : $params['email'];
            }
        } else {
            $email = $customer->getEmail();
        }

        if (isset($email)) {
            $params['onlyUpdate'] = true;
            Mage::helper('mailchimp')->preFilter($email, $params);
        }

        return $this;
    }

    public function ecomm360($observer) {

        $order = $observer->getEvent()->getOrder();
        Mage::getSingleton('mailchimp/ecomm360')->runEcomm360($order);
        return $this;
    }

    public function registerMe() {

        Mage::getSingleton('mailchimp/ecomm360')->registerMe();
        return $this;
    }

}

?>
