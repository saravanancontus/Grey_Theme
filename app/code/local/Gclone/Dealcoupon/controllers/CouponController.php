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
class Gclone_Dealcoupon_CouponController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $this->loadLayout('print');
        $orderId = Mage::app()->getRequest()->getParam('order_id');
        $_order = Gclone_Dealcoupon_Block_Coupon::getOrder($orderId);
        $id = Mage::app()->getRequest()->getParam('id');
        $uniquenumber = '';
        if ($id == '') {
            $id = 1;
        }
        $resource = Mage::getSingleton('core/resource');
        $couponemail = $resource->getConnection('core_write');
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $uniquenumber = '';
        
        $giftCouponCheck = $couponemail->fetchRow("select * from " . $tprefix . "ordercustomer where order_id ='" . $orderId . "' and quantitynumber ='" . $id . "'");

        if (isset($giftCouponCheck['uniqueid'])) {
            $uniquenumber = $giftCouponCheck['uniqueid'];
            $customerName = $giftCouponCheck['customer_name'];
        }
        //pdf download start
        $pdf = Mage::app()->getRequest()->getParam('pdf');
        
        if (isset($pdf)) {
            $pdfRender = Gclone_Dealcoupon_Block_Coupon::getCouponPdf($_order, $orderId, $id, $uniquenumber);
            $this->_prepareDownloadResponse('Coupon #' . $uniquenumber . '.pdf', $pdfRender->render(), 'application/pdf');
        }
        
        //pdf download end
        $this->renderLayout();
    }

}