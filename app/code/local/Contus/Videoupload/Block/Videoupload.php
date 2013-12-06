<?php
/**
 * Contus Support Interactive.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file GCLONE-LICENSE.txt.
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento 1.4.1.1 COMMUNITY edition
 * Contus Support does not guarantee correct work of this package
 * on any other Magento edition except Magento 1.4.1.1 COMMUNITY edition.
 * =================================================================
 */

/*
 * Contus Support Pvt Ltd.
 * created by Vasanth on nov 04 2010.
 * vasanth@contus.in
 * In this page used to fetch video collections and Product collection.
 */
?>
<?php
class Contus_Videoupload_Block_Videoupload extends Mage_Core_Block_Template
{
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
    /*Get Video Collection*/
    public function getVideoupload($id)
    {

       $product = $this->getProduct();
       $_videoCollection = Mage::getModel('videoupload/videoupload');
       $_videoCollection = $_videoCollection->getCollection();
       /*Filter videoCollection using product id and status is enable*/
        $_videoCollection->addFieldToFilter('entity_id',Array('eq'=>$id));
        $_videoCollection->addFieldToFilter('status',Array('eq'=>'1'));
        return $_videoCollection;

    }
    /*Get Pdoduct Collection*/
    public function getProduct()
    {
        if (!Mage::registry('product') && $this->getProductId()) {

            $product = Mage::getModel('catalog/product')->load($this->getProductId());
            Mage::register('product', $product);
        }
        return Mage::registry('product');
    }
    /*Get Action url for this module*/
    public function getFormActionUrl()
    {
        return $this->getUrl('videoupload', array('_secure' => true));
    }



}