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
class Gclone_Deal_Block_Sidedeal_View extends Mage_Core_Block_Template {

    /**
     * Initialize template
     *
     */
    protected function _construct() {
        $this->setTemplate('deal/sidedeal/view.phtml');
    }

    public function getQuantityPurchase($_product) {
        $current_product = $_product->getId();
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('catalog_read');
        $orderTable = $resource->getTableName('sales/order');
        $orderItemTable = $resource->getTableName('sales/order_item');
        $dealstatus[0] = "processing";
        $dealstatus[2] = "complete";
        $select = $read->select()
                        ->from(array('cp' => $orderTable), array('totalcount' => 'sum(cp.total_qty_ordered)'))
                        ->join(array('pei' => $orderItemTable), 'pei.order_id=cp.entity_id', array())
                        ->where('cp.status in (?)', $dealstatus)
                        ->where('pei.product_id in (?)', $current_product);
        $orders_list = $read->fetchAll($select);
        $quantity[0] = floor($orders_list[0]['totalcount']);
        if ($_product->gettarget_number() != '') {
            $percent_deal = ($quantity[0] / $_product->gettarget_number()) * 100;
            //$paypalPaymentAction = $resource->getConnection('core_write');
            $tprefix = (string) Mage::getConfig()->getTablePrefix();

            if ($_product->gettarget_number() > $quantity[0]) {
                //$paypalPaymentAction->query("update ".$tprefix."core_config_data set value = 'Authorization' where path = 'payment/paypal_standard/payment_action'" );
                $targetNumber = $_product->gettarget_number() - $quantity[0];
                $targetNumber = $this->__('%s more needed to get the deal', $targetNumber);
            } else {
                $targetNumber = $this->__('Deal Achieved! Keep Buying!');
                // $paypalPaymentAction->query("update ".$tprefix."core_config_data set value = 'Sale' where path = 'payment/paypal_standard/payment_action'" );
            }
        } else {
            $percent_deal = '100';
        }
        $percent_deal = round($percent_deal);
        if ($percent_deal > 0 && $percent_deal <= 99) {

        }
        if ($percent_deal == 0) {
            $percent_deal = "0";
        }
        if ($percent_deal > 0 && $percent_deal <= 15) {
            $percent_deal = "1";
        }
        if ($percent_deal >= 16 && $percent_deal <= 33) {
            $percent_deal = "2";
        }
        if ($percent_deal >= 34 && $percent_deal <= 54) {
            $percent_deal = "3";
        }
        if ($percent_deal >= 55 && $percent_deal <= 73) {
            $percent_deal = "4";
        }
        if ($percent_deal >= 74 && $percent_deal <= 86) {
            $percent_deal = "5";
        }
        if ($percent_deal > 87 && $percent_deal <= 99) {
            $percent_deal = "6";
        }
        if ($percent_deal >= 100) {
            $percent_deal = "7";
        }
        $result[] = $quantity[0];
        $result[] = $percent_deal;
        $result[] = $targetNumber;
        return $result;
    }
}
?>