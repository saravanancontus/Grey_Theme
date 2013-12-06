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

class Gclone_Deal_Block_Upcoming extends Mage_Core_Block_Template {

     protected function _prepareLayout() {
        $this->setToolbar($this->getLayout()->createBlock('page/html_pager', 'deal_upcoming_toolbar'));
        $this->getToolbar()->setCollection($this->_getTransaction());
    }

    public function _getTransaction() {
        $cityId = Mage::getModel('deal/deal')->fetchCity();
        if($this->getPageSize()) $pagesize = $this->getPage_size();
        $dealVisibility[0]='2';
	$dealVisibility[1]='4';
        $currentDate = Mage::getModel('core/date')->date('Y-m-d');
        $_productCollection = Mage::getModel('catalog/product')->getCollection()
                            ->addAttributeToSort('special_from_date', 'ASC')
                             ->addAttributeToFilter('dealcity', array('finset' => $cityId))
                            ->addAttributeToFilter('status', array('eq' => '1'))
                            ->addAttributeToFilter('special_from_date', array('gteq' => $currentDate));

        $currentTime = Mage::getModel('core/date')->date('Y-m-d H:i:s');
        foreach ($_productCollection as $rows) {
            $storeId = Mage::app()->getStore()->getId();
            $products[] = Mage::getModel('catalog/product')->setStoreId($storeId)->load($rows->getId());
        }
        if (count($products) > 0) {
            foreach ($products as $_product) {
                //code to check if the deal is current or not, if deal is current, deal will not be displayed, elseif the deal time is over then deal will be displayed
                $startTime = $_product->getstarttime();
                $customersite = $_product->getcustomersite();
                $ProductToDate = $_product->getResource()->formatDate($_product->getspecial_to_date(), false);
                $ProductFromDate = $_product->getResource()->formatDate($_product->getspecial_from_date(), false);
                $Fromtime = strtotime($ProductFromDate . ' ' . $startTime);
                if ($Fromtime > strtotime($currentTime)) {
                        $productIds[] = $_product->getId();
                }
            }
        }
        $_products = Mage::getModel('catalog/product')->getCollection()
                            ->addAttributeToFilter('entity_id', array('in' => $productIds));
        return $_products;
    }

    public function getTransaction() {
        return $this->getToolbar()->getCollection();
    }

    public function getToolbarHtml() {
        return $this->getToolbar()->toHtml();
    }
     public function getUpcomingProduct($limit = null, $offset=null)
    {
        $upcomingProducts = Mage::getModel('deal/deal')->getUpcomingProducts();
        return $upcomingProducts;
    }

}

?>