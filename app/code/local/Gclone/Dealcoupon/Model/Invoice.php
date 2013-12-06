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
class Gclone_Dealcoupon_Model_Invoice extends Gclone_Dealcoupon_Model_Abstract {
    /*
     * To create a PDF document
     * updated @ 02.12.2010
     */

    //coupon pdf creation
    public function getCouponPdf($order = array(), $orderId, $identifier) {
        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');

        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        $page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
        $pdf->pages[] = $page;

        $this->insertCoupon($page, $order, $orderId, Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, '1'), $identifier);
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(1));

        /* Add image */



        $this->_afterGetPdf();

        return $pdf;
    }

}
