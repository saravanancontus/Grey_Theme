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
class Gclone_Couponvalidator_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {

        $this->loadLayout();
        $this->renderLayout();
    }

    public function updateAction() {
        $update = Mage::app()->getRequest()->getParam('update');
        if (isset($update)) {
            $currentTime = Mage::getModel('core/date')->date('Y-m-d H:i:s');
         
            $updatedDate = strtotime($currentTime);
            $date = date("Y-m-d, H:i:s", $updatedDate);
            $resource = Mage::getSingleton('core/resource');
            $couponUpdate = $resource->getConnection('core_write');
            $tprefix = (string) Mage::getConfig()->getTablePrefix();
            $couponUpdate->update($resource->getTableName('ordercustomer'),
                   array('couponstatus' => '2','updated_on' => $date,),
                   array('ordercustomer_id = ?' => $update,)
           );
        }
        $this->_redirectReferer();
    }

    public function couponvalidationAction() {

        $validMerchant = NULL;
        $code = $this->getRequest()->getParam('code');
        $code = preg_replace("/[^0-9a-zA-Z]/i", '', $code);
        $validMerchant = $this->getRequest()->getParam('valid_merchant');

        //Retrieve the read connection
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();

        //To verify the coupon code
        $select = $readConnection->select()
                    ->from(array('p' => $resource->getTableName('ordercustomer')), new Zend_Db_Expr('count(ordercustomer_id) as result,ordercustomer_id,order_id,customer_name,uniqueid,product_name,created_time,updated_on,quantity,quantitynumber,couponstatus'))
                    ->where('p.uniqueid = ?',$code);
        $response = $readConnection->fetchRow($select);

        if(isset($validMerchant)){
            $response['validMerchant'] =$validMerchant;
        }        
        Mage::register("response", $response);
        
        $this->loadLayout();
        $this->renderLayout();
    }

    public function validAction() { 
        if ($data = $this->getRequest()->getPost()) {  
            $merchant_ID = $data["merchant_ID"] - 1000;
            $uniqueid = $data["uniqueid"];
            $order_id = $data["order_id"];

            if (substr($order_id, 0, 5) === '10000') // fix for bonus coupons
                $order_id = substr($order_id, 5);

            //Load merchantData from order
            $order = Mage::getModel('sales/order')->load($order_id);
            $originalMerchantId = $order->getData('merchant_id');            
            if ($originalMerchantId == $merchant_ID) {
                $currentTime = Mage::app()->getLocale()->date(null, null, "en_US", true);
                $updatedDate = strtotime($currentTime);
                $date = date("Y-m-d, H:i:s");
                $resource = Mage::getSingleton('core/resource');
                $couponUpdate = $resource->getConnection('core_write');
                $tprefix = (string) Mage::getConfig()->getTablePrefix();
                $couponUpdate->update($resource->getTableName('ordercustomer'),
                   array('couponstatus' => '2','updated_on' => $date,),
                   array('uniqueid = ?' => $uniqueid,)
                );
                $this->_redirect('*/*/couponvalidation', array('code' => $uniqueid, 'valid_merchant' => 'valid'));
            } else {
                $this->_redirect('*/*/couponvalidation', array('code' => $uniqueid, 'valid_merchant' => 'invalid'));
            }
        }
    }

}