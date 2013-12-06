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

class Gclone_Dealcoupon_Block_Coupon extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getDealcoupon()     
     { 
        if (!$this->hasData('dealcoupon')) {
            $this->setData('dealcoupon', Mage::registry('dealcoupon'));
        }
        return $this->getData('dealcoupon');
        
    }


    public function getPaymentInfoHtml() {
        return $this->getChildHtml('payment_info');
    }

    public function getOrder($orderId) {
        //return Mage::registry('current_order');
        $resource = Mage::getSingleton('core/resource');
        $currentdeal = $resource->getConnection('core_write');
        $read = $resource->getConnection('catalog_read');
        $orderTable = $resource->getTableName('sales/order');
        $orderItemTable = $resource->getTableName('sales/order_item');
        $selectOrders = $read->select()
                                ->from(array('cp' => $orderTable))
                                ->join(array('pei' => $orderItemTable), 'pei.order_id=cp.entity_id', array())
                                ->where('pei.order_id in (?)', $orderId);
                $orders_list = $read->fetchAll($selectOrders);
        return $orders_list;
    }

    protected function _prepareItem(Mage_Core_Block_Abstract $renderer) {
        $renderer->setPrintStatus(true);

        return parent::_prepareItem($renderer);
    }

    public function getCouponCode($orderId,$id) {

//       $giftCouponCheck = $couponemail->fetchRow("select * from " . $tprefix . "ordercustomer where order_id ='" . $orderId . "' and quantitynumber ='" . $id . "'");

        $resource = Mage::getSingleton('core/resource');
        $orderTable = $resource->getTableName('ordercustomer');
        $read = $resource->getConnection('read');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $select = $read->select()
                        ->from(array('cp' => $orderTable))
                        ->where('cp.order_id=?', $orderId)
                        ->where('cp.quantitynumber=?',$id);

        $orders_list = $read->fetchRow($select);
        return $orders_list;
    }

    public function getProductDetail($productId) {
        $product = Mage::getModel('catalog/product')->load($productId);
        return $product;
    }

    public function getCouponPdf($_order, $orderId, $no, $identifier) {
        $pdf = Mage::getModel('dealcoupon/invoice')->getCouponPdf($_order, $orderId, $no);
        return $pdf;
    }

    public function getCustomoptions($sku,$productdetail,$CustomerOrderId) {
        $productSku = $productdetail->getSku();
        $attVal = $productdetail->getOptions();
        if (sizeof($attVal)) {
            foreach ($attVal as $optionVal) {
                $customTitle = $optionVal->getTitle();
                foreach ($optionVal->getValues() as $valuesKey => $valuesVal) {
                    $sku = $valuesVal->getId();
                    $dealstatus[0] = "processing";
                    $dealstatus[2] = "complete";
                    $collection = Mage::getModel('sales/order')->getCollection()
                                    ->addFieldToFilter('status', array('in' => $dealstatus));
                    $collection->join(
                                    'sales/order_item',
                                    '`sales/order_item`.order_id=`main_table`.entity_id',
                                    array(
                                        'proptions' => new Zend_Db_Expr('group_concat(`sales/order_item`.product_options SEPARATOR ",")'),
                                    )
                            )
                            ->addAttributeToFilter('order_id', array('eq' => $CustomerOrderId));

                    foreach ($collection as $item) {
                        $value = $item->getProptions();
                        $value = unserialize($value);
                        $option_array = $value['options'];
                        $options = "";
                        if (count($option_array) > 0) {
                            foreach ($option_array as $_option) {
                                if ($_option['option_value'] == $sku) {
                                    $customoptionTitle = $valuesVal->getTitle();
                                    if($productdetail->getSpecialPrice()) {
                                        $customPrice = $productdetail->getSpecialPrice();
                                    } else {
                                        $customPrice = $productdetail->getPrice();
                                    }
                                    if($valuesVal->getPriceType() == 'fixed') {
                                        $customPrice = $customPrice + $valuesVal->getPrice();
                                    } else {
                                        $percent = $customPrice * ($valuesVal->getPrice()/100);
                                        $customPrice = $customPrice + $percent;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $customArray[] = array('customtitle' => $customTitle,'customoptiontitle' => $customoptionTitle,'customprice' => $customPrice);
        return $customArray;
    }
}