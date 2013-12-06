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


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('phpqrcode/qrlib.php');

class Gclone_Barcode_Block_Barcode extends Mage_Core_Model_Abstract{
        
    public function generateBarcode($uniquecode,$barcodeFileName){
        
        $baseUrl = Mage::getBaseDir();
        $baseUrl = str_replace('index.php/', '', $baseUrl);
        $PNG_TEMP_DIR = $baseUrl.'/media/barcode/';
        if (!is_dir($PNG_TEMP_DIR)) {
            mkdir($PNG_TEMP_DIR,0777,true);
        }

        $fileData = Mage::getBaseUrl()."couponvalidator/index/couponvalidation/code/".$uniquecode;
        $filename = $PNG_TEMP_DIR . $barcodeFileName.'.png';
        $errorCorrectionLevel = 'L';
        $matrixPointSize = 4;

        QRcode::png($fileData, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
    }

}

?>
