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

class Gclone_Deal_Block_Active extends Mage_Core_Block_Template {

    protected function _prepareLayout() {
        $this->setToolbar($this->getLayout()->createBlock('page/html_pager', 'deal_active_toolbar'));
        $this->getToolbar()->setCollection($this->_getTransaction());
    }

    public function _getTransaction() {
        $cityId = Mage::getModel('deal/deal')->fetchCity();
        if ($this->getPageSize())
            $pagesize = $this->getPage_size();
        $_productCollection = Mage::getModel('catalog/product')->getCollection();
        $dealVisibility[0] = '2';
        $dealVisibility[1] = '4';
        $_productCollection->addAttributeToFilter('visibility', array('in' => $dealVisibility));
        //filter for products status is equal (eq) to enable, and deal status equal (eq) to current
        $_productCollection->addAttributeToFilter('status', array('eq' => '1'));
        $todayDate = date('m/d/y');
        $tomorrow = mktime(0, 0, 0, date('m'), date('d') + 1, date('y'));
        $tomorrowDate = date('m/d/y', $tomorrow);

//	$_productCollection->addAttributeToFilter('special_to_date', array('or'=> array(
//	0 => array('date' => true, 'from' => $tomorrowDate),
//	1 => array('is' => new Zend_Db_Expr('null')))
//	), 'left');
        $currentTime = date('Y-m-d');
        $_productCollection->addAttributeToFilter('dealcity', array('finset' => $cityId));
        $_productCollection->addAttributeToFilter('special_from_date', array('lteq' => $currentTime));
        $_productCollection->addAttributeToFilter('special_to_date', array('gteq' => $currentTime));
        $_productCollection->addAttributeToSort('special_to_date', 'ASC');
        return $_productCollection;
    }

    public function getTransaction() {
        return $this->getToolbar()->getCollection();
    }

    public function getToolbarHtml() {
        return $this->getToolbar()->toHtml();
    }

    public function getActiveProduct($limit = null, $offset = null) {
        $activeProducts = Mage::getModel('deal/deal')->getActiveProducts();
        return $activeProducts;
    }

    public function getHomeDeals($cityId, $catId) {
        //echo $catId;exit;
        if ($catId == null) {
            $category = $this->prepareCategory();
            $config = $this->getConfig('deal/category/maindeal');
            $catId = $config['value'];
        }

        $currentTime = Mage_Core_Model_Locale::date(null, null, "en_US", true);
        $todayDate = Mage::getModel('core/date')->date('Y-m-d');
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
              //  ->addAttributeToFilter('featureddeal', array('eq' => '1'))
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
        $model = Mage::getModel('catalog/product');

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