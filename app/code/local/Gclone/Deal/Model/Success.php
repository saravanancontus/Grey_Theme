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

class Gclone_Deal_Model_Success extends Mage_Checkout_Block_Onepage_Success
{
   

   /* contus */
    public function setGiftOrderId() {
        $sessiongift = Mage::getSingleton('core/session');
        if($sessiongift->getorderemail()) {
            $lastOrderId = Mage::getSingleton('checkout/session')->getLastOrderId();
            $customerId = Mage::getSingleton('customer/session')->getCustomerId();
            $tprefix = (string)Mage::getConfig()->getTablePrefix();
            $write = Mage::getSingleton('core/resource')->getConnection('core_write');
            $giftCouponCheck = $write->fetchRow("select max(gift_message_id) as idgift from ".$tprefix."gift_message");
            $giftid = '';
            if(isset($giftCouponCheck['idgift'])) {
                $giftid = $giftCouponCheck['idgift'];
            }
            $write->query("UPDATE ".$tprefix."gift_message set order_id ='".$lastOrderId."'where order_id  = '0' and customer_id = '".$customerId."' and gift_message_id = '".$giftid."'");
            $sessiongift->setorderemail('');
            $sessiongift->setorderfrom('');
            $sessiongift->setorderto('');
            $sessiongift->setordermessage('');
        }
    }

    /* Function to generate the facebook dialog feed link
     * Share the product you have purchased right now in your facebook profile
     *
    */
    public function getFacebookShare() {

        $orderid = $this->_getData('order_id');
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('catalog_read');
        $orderTable = $resource->getTableName('sales/order');
        $orderItemTable = $resource->getTableName('sales/order_item');

        $select = $read->select()
                        ->from(array('cp' => $orderTable), array('totalcount' => 'sum(cp.total_qty_ordered)', 'orderid' => 'pei.order_id', 'firstname' => 'cp.customer_firstname', 'lastname' => 'cp.customer_lastname'))
                        ->join(array('pei' => $orderItemTable), 'pei.order_id=cp.entity_id', array('productid' => 'pei.product_id'))
                        ->where('cp.increment_id in (?)', $orderid);
        $orders_list = $read->fetchAll($select);

        $quantity[0] = floor($orders_list[0]['totalcount']);
        $orderid = $orders_list[0]['orderid'];
        $customer_name = $orders_list[0]['firstname'] . ' ' . $orders_list[0]['lastname'];
        $giftemail = $resource->getConnection('core_write');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $giftCouponCheck = $giftemail->fetchRow("select * from " . $tprefix . "ordercustomer where order_id ='$orderid'");

        $appId = Mage::getStoreConfig('customer/facebook/api_id');
        $baseUrl = Mage::getBaseUrl();
        $productName = urlencode($giftCouponCheck['product_name']);
        $storeName = urlencode(Mage::getStoreConfig('general/store_information/name'));
        $model = Mage::getModel('catalog/product'); //getting product model
        $productId = $orders_list[0]['productid'];
        $_product = $model->load($productId);
        $_description = urlencode(strip_tags($_product->getdescription()));
        $logoImage=$this->helper('catalog/image')->init($_product, 'image');
        //prepare link for facebook dialog feed
        $url = 'http://www.facebook.com/dialog/feed?app_id=' . $appId . '&link=' . $baseUrl . '&description=' . $_description  . '&picture=' . $logoImage . '&name=' . $productName . '&message=This deal is Great. Check out!!!&redirect_uri=' . $baseUrl;
        return $url;
    }
}