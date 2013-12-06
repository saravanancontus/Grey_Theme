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
class Gclone_Emailnewsletter_Model_Emailnewsletter extends Mage_Core_Model_Abstract {
    const XML_PATH_EMAIL_RECIPIENT = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER = 'emailnewsletter/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE = 'emailnewsletter/email/email_template';

    public function _construct() {
        parent::_construct();
        $this->_init('emailnewsletter/emailnewsletter');
    }

    /* Contus
     * Deciding on the current product
     */

    public function getCurrentProducts() {
        $timezone = explode(" ", Mage::helper('core')->formatDate(null, 'long', true));
        $currentTime = Mage_Core_Model_Locale::date(null, null, "en_US", true);
        $resource1 = Mage::getSingleton("core/resource");
        $currentdeal = $resource1->getConnection('core_write');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $_productCollection = Mage::getModel('catalog/product')->getCollection()->addFieldToFilter(array(
                            array('attribute' => 'Status', 'eq' => '1'),))
                        ->addAttributeToSelect('special_from_date')
                        ->addAttributeToSelect('special_to_date')
                        ->addAttributeToSelect('starttime')
                        ->addAttributeToSelect('time')
                        ->addAttributeToSelect('dealcity');
        $resultproductids = $currentdeal->fetchAll("select product_id from " . $tprefix . "product_newsletter");
        
        
     
        foreach ($resultproductids as $itemIds) {
            foreach ($itemIds as $itemId) {
                $resultproductid[] = $itemId;
            }
        }
      //  echo 'success'; exit;
        //Fetching the customer how had subscribed for that particular City
        $select = $currentdeal->select()
                    ->from(array('p' => $currentdeal->getTableName('newsletter_subscriber')), new Zend_Db_Expr('subscriber_id,store_id,change_status_at,customer_id,subscriber_email,subscriber_city,subscriber_status,subscriber_confirm_code'))
                    ->where('p.subscriber_status = 1');
        $subscriber_list = $currentdeal->fetchAll($select);
        
        foreach ($subscriber_list as $list) {
            $email_list[] = $list;
        }

        //$model = Mage::getModel('catalog/product');
        $cityname = '';
        foreach ($_productCollection as $_product) {
            //$_product = $model->load($_product->getId());
            $current_product = $_product->getId();
            $startTime = $_product->getstarttime();
            $city = $_product->getAttributeText('dealcity');
            $ProductToDate = $_product->getResource()->formatDate($_product->getspecial_to_date(), false);
            $ProductFromDate = $_product->getResource()->formatDate($_product->getspecial_from_date(), false);
            $Fromtime = strtotime($ProductFromDate . ' ' . $startTime);
            if ($Fromtime < strtotime($currentTime)) {
                if (strtotime($ProductToDate . ' ' . $_product->getTime()) > strtotime($currentTime)) {
                    //Query to Checking whether the newsletter had already sent

                    if (!in_array($current_product, $resultproductid)) {
                        // Call Newsletter Function to send Newsletter

                        self::sendProductNewsletter($current_product, $city, $email_list);
                    }
                }
            }
        }
        
    }

    /* Contus
     * Sending Newsletter Function
     */

    private function sendProductNewsletter($current_product, $city, $email_list) {
        $resource1 = Mage::getSingleton('core/resource');
        $currentdeal = $resource1->getConnection('core_write');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $urlCollection = Mage::getModel('deal/deal')->cityCollections();

        //getting product model
        $model = Mage::getModel('catalog/product');

        //currency symbol
        $currencySymbol = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();

        /* Gathering Newsletter detail */
        $productdetail = $model->load($current_product);
        $currentproductname = $productdetail->getName();
        $currentproductimage = Mage::helper('catalog/image')->init($productdetail, 'image');
        if($productdetail->getSpecialPrice()) {
            $discount_price = $productdetail->getPrice() - $productdetail->getSpecialPrice();
            $product_saveprice = $discount_price;
            $discount = ($discount_price * 100) / $productdetail->getPrice();
            $discount = round($discount);            
        } else {
            $product_saveprice = 0;
            $discount = 0;
        }
        $Companywebsite = $productdetail->getCustomer_website();
        $Companyname = $productdetail->getCustomersite();
        $product_worth = $productdetail->getPrice();
        if($productdetail->getSpecialPrice()) {
            $product_price = $productdetail->getSpecialPrice();
        } else {
            $product_price = $productdetail->getPrice();
        }
        
        
        $product_description = $productdetail->getDescription();
        $deal_date = date('M d Y', strtotime(now()));
        //$productUrl = Mage::getModel('deal/deal')->getProductUrl($current_product);
        $productUrl = $productdetail->getProductUrl();
        foreach ($urlCollection as $key => $item) {
            $cities = array();
            if (count($city) > 1 && count($city != 0)) {
                $cities = $city;
            } else {
                $cities[] = $city;
            }

            foreach ($cities as $c) {
                if ($item == $c) {
                    //Customer Email address In an Array
                    $i = 0;
                    $tocustomer = array();
                    if (count($email_list) > 0) {
                        foreach ($email_list as $rows) {
                            if ($rows['subscriber_city'] == $key) {
                                $tocustomer = $rows['subscriber_email'];
                                $subscribeId = $rows['subscriber_id'];
                                $subscribeCode = $rows['subscriber_confirm_code'];
                                $cityToCheck = $item;
                                //Gathering details for the Newsletter Sending(Template)
                                $postObject = new Varien_Object();
                               $product_saveprice = number_format($product_saveprice, 2, '.', '');
                                $product_worth = number_format($product_worth, 2, '.', '');
                                $product_price = number_format($product_price, 2, '.', '');
                                $postObject->setData(array('deal_date' => $deal_date, 'product_description' => $product_description, 'product_saveprice' => $product_saveprice, 'productname' => $currentproductname, 'product_price' => $product_price, 'productimage' => $currentproductimage, 'discount' => $discount, 'companywebsite' => $Companywebsite, 'product_worth' => $product_worth, 'product_city' => $cityToCheck, 'subscribe_id' => $subscribeId, 'subscribe_code' => $subscribeCode, 'company_address' => $Companyname, 'currency_symbol' => $currencySymbol, 'product_url' => $productUrl));
                                $mailTemplate = Mage::getModel('core/email_template');
                                $mailTemplate->setSenderName(Mage::getStoreConfig('design/head/default_title'));
                                $mailTemplate->setSenderEmail(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT));
                                $mailTemplate->setTemplateSubject('Newsletter Subscription From ' . Mage::getStoreConfig('design/head/default_title'));
                                $mailTemplate->addBcc($tocustomer);
                                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                                        ->sendTransactional(
                                                Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                                                Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                                                $tocustomer,
                                                Mage::getStoreConfig('design/head/default_title'),
                                                array('deallist' => $postObject)
                                );
                                $i++;
                            }
                        }
                        if ($i != 0 && $mailTemplate->getSentSuccess()) {
                            //Insterting product ID into the table after sending hre newsletter
                            $insert = $currentdeal->insert(
                               $resource1->getTableName('product_newsletter'),
                               array('product_id' => $current_product, 'email_sent' => '1')
                            );                        
                            
                        }
                    }
                }
            }
        }
    }

}