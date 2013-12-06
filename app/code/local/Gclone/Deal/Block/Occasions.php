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
 * Contus Support Interactive
 */

class Gclone_Deal_Block_Occasions extends Mage_Core_Block_Template {

    protected function _prepareLayout() {
        $this->setToolbar($this->getLayout()->createBlock('page/html_pager', 'deal_getaways_toolbar'));
        $this->getToolbar()->setCollection($this->_getTransaction());
    }

    public function _getTransaction() {
        $cityId = Mage::getModel('deal/deal')->fetchCity();
        if($this->getPageSize()) $pagesize = $this->getPage_size();
        $_productCollection = Mage::getModel('catalog/product')->getCollection();
	$dealVisibility[0]='2';
	$dealVisibility[1]='4';
	$_productCollection->addAttributeToFilter('visibility', array('in' => $dealVisibility));
	//filter for products status is equal (eq) to enable, and deal status equal (eq) to current
	$_productCollection->addAttributeToFilter('status', array('eq' => '1'));
	$todayDate = date('m/d/y');
	$tomorrow = mktime(0, 0, 0, date('m'), date('d')+1, date('y'));
	$tomorrowDate = date('m/d/y', $tomorrow);
        $currentTime = date('Y-m-d');
        $_productCollection->addAttributeToFilter('dealcity', array('finset' => $cityId));
        $_productCollection->addAttributeToFilter('special_from_date', array('lteq' => $currentTime));
        $_productCollection->addAttributeToFilter('special_to_date', array('gteq' => $currentTime));
        $_productCollection->addAttributeToSort('special_to_date','ASC');
        return $_productCollection;
    }

    public function getTransaction() {
        return $this->getToolbar()->getCollection();
    }

    public function getToolbarHtml() {
        return $this->getToolbar()->toHtml();
    }
    public function getActiveProduct($limit = null, $offset=null)
    {
        $goodsProducts = Mage::getModel('deal/deal')->getgoodsProducts();
        return $goodsProducts;
    }
}
?>