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

require_once 'Mage/Newsletter/controllers/SubscriberController.php';

class Gclone_Deal_Newsletter_SubscriberController extends Mage_Newsletter_SubscriberController {

    /**
     * New subscription action
     */
    public function newAction() { 
        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $session = Mage::getSingleton('core/session');
            $customerSession = Mage::getSingleton('customer/session');
            $email = (string) $this->getRequest()->getPost('email');
            $city = (string) $this->getRequest()->getPost('city');
            $urlCollection = Mage::getModel('deal/deal')->cityCollections();
            $cusSession = Mage::getSingleton('customer/session')->getCustomer();
            
            $flag = 0;
            try {
                if (!Zend_Validate::is($email, 'EmailAddress')) {
                    Mage::throwException($this->__('Please enter a valid email address.'));
                }

                if (Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1 &&
                        !$customerSession->isLoggedIn()) {
                    Mage::throwException($this->__('Sorry, but administrator denied subscription for guests. Please <a href="%s">register</a>.', Mage::getUrl('customer/account/create/')));
                }

                $ownerId = Mage::getModel('customer/customer')
                                ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                                ->loadByEmail($email)
                                ->getId();
                if ($ownerId !== null && $ownerId != $customerSession->getId()) {
                    Mage::throwException($this->__('Sorry, but you cannot subscribe for the email address assigned to another user.'));
                }

                /*
                 * guest cannot subscribe second time and the customer can subscribe many times and can edit the subscription from dashboard.
                 * 
                 */
                
                
                    $subscriptionData = Gclone_Deal_Block_Newsletter::getCustomerSubscription();
                    foreach ($subscriptionData as $item) {
                        $subCity = $item['subscriber_city'];
                        if (($item['subscriber_email'] == $email) && ($cusSession->getEmail() != $email)) {
                            $session->addError($this->__('You are been already subscribed to') . ' ' . $this->__($urlCollection[$subCity]) . '. ' . $this->__('Sign-in to edit your subscription.'));
                            $flag = 1;
                        }
                    }
                
                /*
                 * Subscription of newsletter.
                 */
                //Validating the city
                if ($city == '') {
                    $session->addError($this->__('Please enter a valid city.'));
                    $this->_redirectUrl(Mage::getBaseUrl());
                }
                else {
                    if ($flag != 1) {
                        $status = Mage::getModel('newsletter/subscriber')->subscribe($email, $city);

                        if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
                            $session->addSuccess($this->__('Confirmation request has been sent.'));
                        } else {
                            $session->addSuccess($this->__($this->__('You are been subscribed to') . ' ' . $this->__($urlCollection[$city]) . '. ' . $this->__('Please check your email for confirmation.')));
                        }
                    }
                }
            } catch (Mage_Core_Exception $e) {
                $session->addException($e, $this->__('There was a problem with the subscription: %s', $e->getMessage()));
            } catch (Exception $e) {
                $session->addException($e, $this->__('There was a problem with the subscription.'));
            }
        }

        //subscribed city will be loaded in the site.
        $final = Mage::getSingleton('core/session')->getTotalCities();
        foreach ($final as $key => $scity) {
            if ($scity == $city) {
                Mage::getSingleton('core/session')->setCity($key);
            }
        }
        //newsletter subscription as default homepage - starts
        $defaultHome = Mage::getStoreConfig('emailnewsletter/homepage/as_default_homepage');
        $isMobile = Mage::getSingleton('core/session')->getMobile();
        if ($defaultHome == 1 || isset($isMobile)) {
            $task = Mage::app()->getRequest()->getPost('task');
            if ($task == 'confirmSubscribe') {
                $cookieExpires = Mage::getSingleton('core/cookie')->getLifetime();
                Mage::getSingleton('core/cookie')->set('confirmSubscribe',$cookieExpires);
                $this->_redirectUrl(Mage::getBaseUrl());
            } else {
                $this->_redirectReferer();
            }
        } else {
            $this->_redirectReferer();
        }
        //newsletter subscription as default homepage - ends
    }

    public function confirmAction()
    {
        $id    = (int) $this->getRequest()->getParam('id');
        $code  = (string) $this->getRequest()->getParam('code');
        $resource = Mage::getSingleton("core/resource");
        $currentdeal = $resource->getConnection('core_write');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $select = $currentdeal->select()
                    ->from(array('p' => $resource->getTableName('newsletter_subscriber')), new Zend_Db_Expr('subscriber_confirm_code'))
                    ->where('p.subscriber_id = ?',$id);
        $resultproductids = $read->fetchAll($select);  

        foreach ($resultproductids as $rows) {
         $code = $rows['subscriber_confirm_code'];
        }
        if ($id && $code) {
            $subscriber = Mage::getModel('newsletter/subscriber')->load($id);
            $session = Mage::getSingleton('core/session');

            if($subscriber->getId() && $subscriber->getCode()) {
                if($subscriber->confirm($code)) {
                    $session->addSuccess($this->__('Your subscription has been confirmed.'));
                } else {
                    $session->addError($this->__('Invalid subscription confirmation code.'));
                }
            } else {
                $session->addError($this->__('Invalid subscription ID.'));
            }
        }

        $this->_redirectUrl(Mage::getBaseUrl());
    }
}
