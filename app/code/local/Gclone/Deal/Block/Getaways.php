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

class Gclone_Deal_Block_Getaways extends Mage_Core_Block_Template {

    protected function _prepareLayout() {
        $this->setToolbar($this->getLayout()->createBlock('page/html_pager', 'deal_getaways_toolbar'));
        $this->getToolbar()->setCollection($this->_getTransaction());
    }

    public function _getTransaction() {
        $cityId = Mage::getModel('deal/deal')->fetchCity();
        $cat = Mage::getResourceModel('catalog/category_collection')->addFieldToFilter('name', 'Getaways');
        $catId = $cat->getFirstItem()->getEntityId();
        if($this->getPageSize()) $pagesize = $this->getPage_size();
        $dealVisibility[0]='2';
	$dealVisibility[1]='4';
        $currentDate = Mage::getModel('core/date')->date('Y-m-d');
        $_productCollection = Mage::getModel('catalog/product')->getCollection()
                            ->addAttributeToFilter('visibility', array('in' => $dealVisibility))
                             ->addCategoryFilter(Mage::getModel('catalog/category')->load($catId))
                            ->addAttributeToFilter('status', array('eq' => '1'))
                            //->addAttributeToFilter('travel', array('eq' => '1'))
                            ->addAttributeToFilter('dealcity', array('finset' => $cityId))
                            ->addAttributeToFilter('special_from_date', array('lteq' => $currentDate))
                            ->addAttributeToFilter('special_to_date', array('gteq' => $currentDate))
                            ->addAttributeToSort('special_to_date','ASC');

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
                if ($Fromtime < strtotime($currentTime)) {
                    if (strtotime($currentTime) < strtotime($ProductToDate . ' ' . $_product->getTime())) {
                        $productIds[] = $_product->getId();
                    }
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
    public function getActiveProduct($limit = null, $offset=null)
    {
        $goodsProducts = Mage::getModel('deal/deal')->getgoodsProducts();
        return $goodsProducts;
    }
       public function getHomeDeals($cityId, $catId) {
        if ($catId == null) {
            $category = $this->prepareCategory();
            $config = $this->getConfig('deal/category/maindeal');
            $catId = $config['value'];
        }

        $currentTime = Mage_Core_Model_Locale::date(null, null, "en_US", true);
        $todayDate = date('Y-m-d');
        //filter for products status is equal (eq) to enable, and deal status equal (eq) to current
        $_productCollection = Mage::getModel('catalog/product')->getCollection()
                ->addFieldToFilter(array(
                    array('attribute' => 'Status', 'eq' => '1'),))
                ->addAttributeToFilter('special_to_date', array('or' => array(
                        0 => array('date' => true, 'from' => $todayDate),
                        1 => array('is' => new Zend_Db_Expr('null')))
                        ), 'left')
        
                ->addAttributeToFilter('dealcity', array('finset' => $cityId))
                ->addCategoryFilter(Mage::getModel('catalog/category')->load($catId))
                ->addAttributeToFilter('featureddeal', array('eq' => '1'))
                ->addAttributeToSelect('special_to_date')
                ->addAttributeToSelect('special_from_date')
                ->addAttributeToSelect('meta_description')
                ->addAttributeToSelect('meta_keyword')
                ->addAttributeToSelect('meta_title')
                ->addAttributeToSelect('Fineprint')
                ->addAttributeToSelect('hightlight')
                ->addAttributeToSelect('description')
                ->addAttributeToSelect('sitemap')
                ->addAttributeToSelect('CustomerWebsite')
                ->addAttributeToSelect('Questions')
                ->addAttributeToSelect('starttime')
                ->addAttributeToSelect('time')
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('featureddeal')
                ->addAttributeToSelect('price')
                ->addAttributeToSelect('special_price')
               // ->setPageSize(4)
                ->addAttributeToSort('entity_id', 'DESC');
        $deal = 0;
        $meta = array();
        

        $count = 0;

        if (count($_productCollection) > 0) {

            foreach ($_productCollection as $_product) {
                //echo $_product->getId();
                $startTime = $_product->getstarttime();
                $ProductToDate = $_product->getResource()->formatDate($_product->getspecial_to_date(), false);
                $ProductFromDate = $_product->getResource()->formatDate($_product->getspecial_from_date(), false);
                $Fromtime = strtotime($ProductFromDate . ' ' . $startTime);
                if ($Fromtime < strtotime($currentTime)) {
                    if (strtotime($ProductToDate . ' ' . $_product->getTime()) > strtotime($currentTime)) {
                        //Get the Product meta title and description
                        $meta[0] = $_product->getMetaTitle();
                        $deal = 1;
                        $meta[1] = htmlspecialchars($_product->getMetaDescription());
                        $meta[2] = htmlspecialchars($_product->getMetaKeyword());
                        $products[] = $_product->getId();
                    }
                }
            }
        } else {
            $meta[0] = "No Deal Available";
            $meta[1] = "";
            Mage::getSingleton('core/session')->setProductID('');
        }
        if ($deal != 1) {
            $meta[0] = "No Deal Available";
            $meta[1] = "";
            Mage::getSingleton('core/session')->setProductID('');
        }
        
        return $products;
    }
 
           
}
?>