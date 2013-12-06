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
class Gclone_Dealcoupon_Model_Coupon extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('dealcoupon/coupon');
    }

    public function getCouponUrl($order) {
        return $this->getUrl('sales/order/coupon', array('order_id' => $order->getId()));
    }

    /*
     * To create a PDF document
     *
     */

    public function getCouponPdf($_order, $no, $identifier) {
        $pdf = Mage::getModel('sales/order_pdf_invoice')->getCouponPdf($_order, $identifier);
        return Mage::getModel('deal/download')->downloadPdf('Coupon #' . $no . '.pdf', $pdf->render(), 'application/pdf');
    }

}