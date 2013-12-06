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
class Gclone_Deal_Model_Observer {

   
    public function perCustomerCheck($observer) {

        foreach (Mage::getSingleton('checkout/session')->getQuote()->getItemsCollection() as $item) {
            Mage::getSingleton('checkout/cart')->removeItem($item->getId())->save();
        }
        Mage::getSingleton('checkout/session')->clear();
        return;
    }

    public function processOnOrderSave($observer) {
//        $order = $observer->getEvent()->getOrder();
//        $orderid = $order->getIncrementId();
         $order = $observer->getEvent()->getOrder();
	 $last_orderid = $order->getId();
		$orderid = $order->getIncrementId();
                $this->sendmerchantid($last_orderid);
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('catalog_read');
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $orderTable = $resource->getTableName('sales/order');
        $orderItemTable = $resource->getTableName('sales/order_item');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $siteTurnOverTable = $tprefix . "site_turnover";

        $select = $read->select()
                        ->from(array('cp' => $orderTable), array('totalcount' => 'sum(cp.total_qty_ordered)', 'orderid' => 'pei.order_id', 'firstname' => 'cp.customer_firstname', 'lastname' => 'cp.customer_lastname', 'entityid' => 'cp.entity_id'))
                        ->join(array('pei' => $orderItemTable), 'pei.order_id=cp.entity_id', array('productid' => 'pei.product_id'))
                        ->where('cp.increment_id in (?)', $orderid);
        $orders_list = $read->fetchAll($select);

        $quantity = floor($orders_list[0]['totalcount']);
        $orderid = $orders_list[0]['orderid'];
        $order_id = $orders_list[0]['entityid'];
        $model = Mage::getModel('catalog/product'); //getting product model
        $productId = $orders_list[0]['productid'];
        $_product = $model->load($productId);

        //site turnover
        $pri = $_product->getPrice() - $_product->getSpecialPrice();
        $saved = $pri * $quantity;

        //Checking condition to avoid duplication insertion
        $checkDuplicate = $read->fetchRow("select product_id from $siteTurnOverTable where product_id = '$productId' and order_id = '$order_id'");
        $checkDuplicatepid = $checkDuplicate['product_id'];
        if ($checkDuplicatepid == '') {
            $executeQuery = $read->query("insert into $siteTurnOverTable values ('',$productId,$order_id,$saved,$quantity)");
        }
        $sessiongift = Mage::getSingleton('core/session');
        if ($sessiongift->getorderemail()) {
            $lastOrderId = $order->getEntityId();
            $customerId = Mage::getSingleton('customer/session')->getCustomerId();
            $tprefix = (string) Mage::getConfig()->getTablePrefix();
            $write = Mage::getSingleton('core/resource')->getConnection('core_write');
            $giftCouponCheck = $write->fetchRow("select max(gift_message_id) as idgift from " . $tprefix . "gift_message");
            $giftid = '';
            if (isset($giftCouponCheck['idgift'])) {
                $giftid = $giftCouponCheck['idgift'];
            }

            $write->query("UPDATE " . $tprefix . "gift_message set order_id ='" . $lastOrderId . "'where order_id  = '0' and customer_id = '" . $customerId . "' and gift_message_id = '" . $giftid . "'");
            $sessiongift->setorderemail('');
            $sessiongift->setorderfrom('');
            $sessiongift->setorderto('');
            $sessiongift->setordermessage('');
        }
        
       
        
    }
    
    public function cart_logout()
    {
        $cartHelper = Mage::helper('checkout/cart');
        //Get all items from cart
        $items = $cartHelper->getCart()->getItems();
        //Loop through all of cart items
        foreach ($items as $item) {
            $itemId = $item->getItemId();
            //Remove items, one by one
            $cartHelper->getCart()->removeItem($itemId)->save();
        }

     Mage::getSingleton('checkout/session')->clear();
     Mage::getSingleton('checkout/cart')->truncate();
    }
 public function sendmerchantid($order_id)
         {

             $resource1 = Mage::getSingleton('core/resource');
		$currentdeal = $resource1->getConnection('core_write');
		$tprefix = (string) Mage::getConfig()->getTablePrefix();

		$order = Mage::getModel('sales/order')->load($order_id);
		$incrementId = $order->getIncrementId();
		$items = $order->getAllItems();
		$products = array();

		foreach ($items as $itemId => $item) {
			$model = Mage::getModel('catalog/product');
			$productId = $item->getProductId();
			$productdetail = $model->load($item->getProductId());
			$incrementId = $productdetail->getOrderPrefix() . $incrementId;
			$merchantId = $productdetail->getMerchant();
		}
		 $resultproductid = $currentdeal->query("update  " . $tprefix . "sales_flat_order set increment_id='$incrementId', merchant_id='$merchantId' where entity_id  = '$order_id'");
		$resultproductid = $currentdeal->query("update  " . $tprefix . "sales_flat_order_grid set increment_id='$incrementId', merchant_id='$merchantId' where entity_id  = '$order_id'");

             }
}