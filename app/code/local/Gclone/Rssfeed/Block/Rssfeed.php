<?php

class Gclone_Rssfeed_Block_Rssfeed extends Mage_Core_Block_Template {

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
    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getRssfeed() {
        if (!$this->hasData('rssfeed')) {
            $this->setData('rssfeed', Mage::registry('rssfeed'));
        }
        return $this->getData('rssfeed');
    }

    public function generateActiveFeeds() {
        // Create array to store the RSS feed entries
        $entries = array();

        $storeId = Mage::app()->getStore()->getStoreId();

        $newurl = Mage::getUrl('rssfeed/index/feed/store_id/' . $storeId);
        $title = $this->__('New Products from %s', Mage::app()->getStore()->getGroup()->getName());
        $lang = Mage::getStoreConfig('general/locale/code');
        $product = Mage::getModel('catalog/product');
        $todayDate = $product->getResource()->formatDate(time());

        $_productCollection = $product->getCollection()
                        ->setStoreId($storeId);
        $_productCollection->addFieldToFilter(array(
            array('attribute' => 'Status', 'eq' => '1'),
        ));

        $_productCollection->addAttributeToSelect('special_from_date');
        $_productCollection->addAttributeToSelect('special_to_date');
        $_productCollection->addAttributeToSelect('starttime');
        $_productCollection->addAttributeToSelect('time');
        $_productCollection->addAttributeToSelect('name');
        $_productCollection->addAttributeToSelect('description');
        $_productCollection->addAttributeToSelect('fineprint');
        //$_productCollection->addAttributeToSelect('dealcategory');
        $_productCollection->addAttributeToSelect('thumbnail');

        $currentTime = Mage_Core_Model_Locale::date(null, null, "en_US", true);

        if (count($_productCollection) > 0) {
            foreach ($_productCollection as $_product) {
                $productUrl = $_product->getProductUrl();
                $final = Mage::getSingleton('core/session')->getTotalCities();
                //$catName = $_product->getAttributeText('dealcategory');
                $catName = 'Main Deal';
                $galleryImages = (string)Mage::helper('catalog/image')->init($_product, 'thumbnail');
                $ProductToDate = $_product->getResource()->formatDate($_product->getspecial_to_date(), false);
                $ProductFromDate = $_product->getResource()->formatDate($_product->getspecial_from_date(), false);
                $Fromtime = strtotime($ProductFromDate);
                if ($Fromtime < strtotime($currentTime)) {
                    if (strtotime($ProductToDate . ' ' . $_product->getTime()) > strtotime($currentTime)) {
                        if (isset($galleryImages)) {
                            $productImage = $galleryImages;
                        } else {
                            $productImage = Mage::getBaseUrl() . 'media/catalog/product/placeholder/default/nodeal.jpg';
                        }

                        $entry = array('title' => $_product->getName(),
                            'description' => strip_tags($_product->getdescription()),
                            'link' => $productUrl,
                            'content' => $_product->getFineprint(),
                            'lastUpdate' => $Fromtime,
                            'category' => array(                                
                                array(
                                    'term' => $this->__('All Deals'),
                                )
                            ),
//                            'source' => array(
//                                'title' => $final[$_product->getcities()],
//                                'url' => Mage::getBaseUrl() . str_replace(' ', '+', $final[$_product->getcities()]),
//                            ),
                            'enclosure' => array(
                                array(
                                    'url' => $productImage,
                                    'type' => 'image/jpeg',
                                    'length' => '150'
                            ))
                        );

                        array_push($entries, $entry);
                    }
                }
            }
        }


        $configTitle = Mage::getStoreConfig('rssfeed/active/feedtitle');
        if ($configTitle == '') {
            $configTitle = Mage::getStoreConfig('general/store_information/name').' Feeds';            
        }
        $configLink = Mage::getStoreConfig('rssfeed/rssfeed/link');
        if ($configLink == '') {
            $configLink = Mage::getBaseUrl();
        }
        // Create the RSS array
        $rss = array(
            'title' => $configTitle,
            'link' => $configLink,
            'language' => $lang,
            'charset' => 'UTF-8',
            'entries' => $entries
        );

        // Import the array
        $feed = Zend_Feed::importArray($rss, 'rss');

        // Write the feed to a variable
        $rssFeed = $feed->saveXML();


        $phpSelf = $_SERVER['SCRIPT_FILENAME'];
        $link = explode('index.php', $phpSelf);
       
        $url = $link[0];

        $file = array();
        if (isset($url)) {
            $file['dom'] = $url . '/media/rss/active/' . $storeId . '.xml';
        }
        $baseUrl = Mage::getBaseUrl();
        $baseUrl = explode('index.php', $baseUrl);
        $file['download'] = $baseUrl[0] . 'media/rss/active/' . $storeId . '.xml';
        // Write the feed to a file residing in /media/rss/active/
        $fh = fopen($file['dom'], "w");
        fwrite($fh, $rssFeed);
        fclose($fh);
        return $file;
    }

    public function generateUpcomingFeeds() {
        // Create array to store the RSS feed entries
        $entries = array();

        $storeId = Mage::app()->getStore()->getStoreId();

        $newurl = Mage::getUrl('rssfeed/index/feed/store_id/' . $storeId);
        $title = $this->__('New Products from %s', Mage::app()->getStore()->getGroup()->getName());
        $lang = Mage::getStoreConfig('general/locale/code');
        $product = Mage::getModel('catalog/product');
        $todayDate = $product->getResource()->formatDate(time());

        $_productCollection = $product->getCollection()
                        ->setStoreId($storeId);
        $_productCollection->addFieldToFilter(array(
            array('attribute' => 'Status', 'eq' => '1'),
        ));

        $_productCollection->addAttributeToSelect('special_from_date');
        $_productCollection->addAttributeToSelect('special_to_date');
        $_productCollection->addAttributeToSelect('starttime');
        $_productCollection->addAttributeToSelect('time');
        $_productCollection->addAttributeToSelect('name');
        $_productCollection->addAttributeToSelect('description');
        $_productCollection->addAttributeToSelect('fineprint');
        //$_productCollection->addAttributeToSelect('dealcategory');
        $_productCollection->addAttributeToSelect('thumbnail');

        $currentTime = Mage_Core_Model_Locale::date(null, null, "en_US", true);
        if (count($_productCollection) > 0) {
            foreach ($_productCollection as $_product) {
                $productUrl = $_product->getProductUrl();
                $final = Mage::getSingleton('core/session')->getTotalCities();
                //$catName = $_product->getAttributeText('dealcategory');
                //$catName = 'Main Deal';
                $galleryImages = (string)Mage::helper('catalog/image')->init($_product, 'thumbnail');
                $ProductToDate = $_product->getResource()->formatDate($_product->getspecial_to_date(), false);
                $ProductFromDate = $_product->getResource()->formatDate($_product->getspecial_from_date(), false);
                $Fromtime = strtotime($ProductFromDate. ' ' . $startTime);
                $startTime = $_product->getstarttime();
                $ToDate = strtotime($ProductToDate);
                $Fromtime = strtotime($ProductFromDate. ' ' . $startTime);
              // if($Fromtime < strtotime($currentTime)) {

                         if (isset($galleryImages)) {
                            $productImage = $galleryImages;
                        } else {
                            $productImage = Mage::getBaseUrl() . 'media/catalog/product/placeholder/default/nodeal.jpg';
                        }

                        $entry = array('title' => $_product->getName(),
                            'description' => strip_tags($_product->getdescription()),
                            'link' => $productUrl,
                            'content' => $_product->getFineprint(),
                            'lastUpdate' => $Fromtime,
                            'category' => array(
                               array(
                                    'term' => $this->__('Upcoming Deals'),
                                )
                            ),
                            'enclosure' => array(
                                array(
                                    'url' => $productImage,
                                    'type' => 'image/jpeg',
                                    'length' => '150'
                            ))
                        );

                        array_push($entries, $entry);
                    //}
                }
            }
     

        $configTitle = Mage::getStoreConfig('rssfeed/upcoming/feedtitle');
        if ($configTitle == '') {
            $configTitle = Mage::getStoreConfig('general/store_information/name').' Feeds';
        }
        $configLink = Mage::getStoreConfig('rssfeed/rssfeed/link');
        if ($configLink == '') {
            $configLink = Mage::getBaseUrl();
        }
        // Create the RSS array
        $rss = array(
            'title' => $configTitle,
            'link' => $configLink,
            'language' => $lang,
            'charset' => 'UTF-8',
            'entries' => $entries
        );

        // Import the array
        $feed = Zend_Feed::importArray($rss, 'rss');

        // Write the feed to a variable
        $rssFeed = $feed->saveXML();


        $phpSelf = $_SERVER['SCRIPT_FILENAME'];
        $link = explode('index.php', $phpSelf);

        $url = $link[0];

        $file = array();
        if (isset($url)) {
            $file['dom'] = $url . '/media/rss/upcoming/' . $storeId . '.xml';
        }
        $baseUrl = Mage::getBaseUrl();
        $baseUrl = explode('index.php', $baseUrl);
        $file['download'] = $baseUrl[0] . 'media/rss/upcoming/' . $storeId . '.xml';
        // Write the feed to a file residing in /media/rss/upcoming/
        $fh = fopen($file['dom'], "w");
        fwrite($fh, $rssFeed);
        fclose($fh);
        return $file;
    }

    public function generateGoodsFeeds() { 
       // Create array to store the RSS feed entries
        $entries = array();
        $storeId = Mage::app()->getStore()->getStoreId();
  $cityId = Mage::getModel('deal/deal')->fetchCity();
        $cat = Mage::getResourceModel('catalog/category_collection')->addFieldToFilter('name', 'Goods');
        $catId = $cat->getFirstItem()->getEntityId();
        $newurl = Mage::getUrl('rssfeed/index/feed/store_id/' . $storeId);
        $title = $this->__('New Products from %s', Mage::app()->getStore()->getGroup()->getName());
        $lang = Mage::getStoreConfig('general/locale/code');
        $product = Mage::getModel('catalog/product');
        $todayDate = $product->getResource()->formatDate(time());

        $_productCollection = $product->getCollection()
                        ->setStoreId($storeId);
        $_productCollection->addFieldToFilter(array(
            array('attribute' => 'Status', 'eq' => '1'),
        ));
         $_productCollection->addCategoryFilter(Mage::getModel('catalog/category')->load($catId));

        $_productCollection->addAttributeToSelect('special_from_date');
        $_productCollection->addAttributeToSelect('special_to_date');
        $_productCollection->addAttributeToSelect('starttime');
        $_productCollection->addAttributeToSelect('time');
        $_productCollection->addAttributeToSelect('name');
        $_productCollection->addAttributeToSelect('description');
        $_productCollection->addAttributeToSelect('fineprint');
        //$_productCollection->addAttributeToSelect('dealcategory');
        $_productCollection->addAttributeToSelect('thumbnail');

        $currentTime = Mage_Core_Model_Locale::date(null, null, "en_US", true);

        if (count($_productCollection) > 0) {
            foreach ($_productCollection as $_product) {
                $productUrl = $_product->getProductUrl();
                $final = Mage::getSingleton('core/session')->getTotalCities();
                //$catName = $_product->getAttributeText('dealcategory');
                $catName = 'Main Deal';
                $galleryImages = (string)Mage::helper('catalog/image')->init($_product, 'thumbnail');
                $ProductToDate = $_product->getResource()->formatDate($_product->getspecial_to_date(), false);
                $ProductFromDate = $_product->getResource()->formatDate($_product->getspecial_from_date(), false);
                $Fromtime = strtotime($ProductFromDate);
                if ($Fromtime < strtotime($currentTime)) {
                    if (strtotime($ProductToDate . ' ' . $_product->getTime()) > strtotime($currentTime)) {
                        if (isset($galleryImages)) {
                            $productImage = $galleryImages;
                        } else {
                            $productImage = Mage::getBaseUrl() . 'media/catalog/product/placeholder/default/nodeal.jpg';
                        }

                        $entry = array('title' => $_product->getName(),
                            'description' => strip_tags($_product->getdescription()),
                            'link' => $productUrl,
                            'content' => $_product->getFineprint(),
                            'lastUpdate' => $Fromtime,
                            'category' => array(
                                array(
                                    'term' => $this->__('Goods Deals'),
                                )
                            ),
//                            'source' => array(
//                                'title' => $final[$_product->getcities()],
//                                'url' => Mage::getBaseUrl() . str_replace(' ', '+', $final[$_product->getcities()]),
//                            ),
                            'enclosure' => array(
                                array(
                                    'url' => $productImage,
                                    'type' => 'image/jpeg',
                                    'length' => '150'
                            ))
                        );

                        array_push($entries, $entry);
                    }
                }
            }
        }


        $configTitle = Mage::getStoreConfig('rssfeed/goods/feedtitle');
        if ($configTitle == '') {
            $configTitle = Mage::getStoreConfig('general/store_information/name').' Feeds';
        }
        $configLink = Mage::getStoreConfig('rssfeed/rssfeed/link');
        if ($configLink == '') {
            $configLink = Mage::getBaseUrl();
        }
        // Create the RSS array
        $rss = array(
            'title' => $configTitle,
            'link' => $configLink,
            'language' => $lang,
            'charset' => 'UTF-8',
            'entries' => $entries
        );

        // Import the array
        $feed = Zend_Feed::importArray($rss, 'rss');

        // Write the feed to a variable
        $rssFeed = $feed->saveXML();


        $phpSelf = $_SERVER['SCRIPT_FILENAME'];
        $link = explode('index.php', $phpSelf);

        $url = $link[0];

        $file = array();
        if (isset($url)) {
            $file['dom'] = $url . '/media/rss/goods/' . $storeId . '.xml';
        }
        $baseUrl = Mage::getBaseUrl();
        $baseUrl = explode('index.php', $baseUrl);
        $file['download'] = $baseUrl[0] . 'media/rss/goods/' . $storeId . '.xml';
        // Write the feed to a file residing in /media/rss/active/
        $fh = fopen($file['dom'], "w");
        fwrite($fh, $rssFeed);
        fclose($fh);
        return $file;
    }


     public function generateGetawaysFeeds() {
        // Create array to store the RSS feed entries
        $entries = array();

        $storeId = Mage::app()->getStore()->getStoreId();

        $newurl = Mage::getUrl('rssfeed/index/feed/store_id/' . $storeId);
        $title = $this->__('New Products from %s', Mage::app()->getStore()->getGroup()->getName());
        $lang = Mage::getStoreConfig('general/locale/code');
        $product = Mage::getModel('catalog/product');
        $todayDate = $product->getResource()->formatDate(time());

        $_productCollection = $product->getCollection()
                        ->setStoreId($storeId);
        $_productCollection->addFieldToFilter(array(
            array('attribute' => 'Status', 'eq' => '1'),
        ));
        $_productCollection->addAttributeToFilter('travel', array('eq' => '1'));

        $_productCollection->addAttributeToSelect('special_from_date');
        $_productCollection->addAttributeToSelect('special_to_date');
        $_productCollection->addAttributeToSelect('starttime');
        $_productCollection->addAttributeToSelect('time');
        $_productCollection->addAttributeToSelect('name');
        $_productCollection->addAttributeToSelect('description');
        $_productCollection->addAttributeToSelect('fineprint');
        //$_productCollection->addAttributeToSelect('dealcategory');
        $_productCollection->addAttributeToSelect('thumbnail');

        $currentTime = Mage_Core_Model_Locale::date(null, null, "en_US", true);

        if (count($_productCollection) > 0) {
            foreach ($_productCollection as $_product) {
                $productUrl = $_product->getProductUrl();
                $final = Mage::getSingleton('core/session')->getTotalCities();
                //$catName = $_product->getAttributeText('dealcategory');
                $catName = 'Main Deal';
                $galleryImages = (string)Mage::helper('catalog/image')->init($_product, 'thumbnail');
                $ProductToDate = $_product->getResource()->formatDate($_product->getspecial_to_date(), false);
                $ProductFromDate = $_product->getResource()->formatDate($_product->getspecial_from_date(), false);
                $Fromtime = strtotime($ProductFromDate);
                if ($Fromtime < strtotime($currentTime)) {
                    if (strtotime($ProductToDate . ' ' . $_product->getTime()) > strtotime($currentTime)) {
                        if (isset($galleryImages)) {
                            $productImage = $galleryImages;
                        } else {
                            $productImage = Mage::getBaseUrl() . 'media/catalog/product/placeholder/default/nodeal.jpg';
                        }

                        $entry = array('title' => $_product->getName(),
                            'description' => strip_tags($_product->getdescription()),
                            'link' => $productUrl,
                            'content' => $_product->getFineprint(),
                            'lastUpdate' => $Fromtime,
                            'category' => array(
                                array(
                                    'term' => $this->__('Getaways Deals'),
                                )
                            ),
//                            'source' => array(
//                                'title' => $final[$_product->getcities()],
//                                'url' => Mage::getBaseUrl() . str_replace(' ', '+', $final[$_product->getcities()]),
//                            ),
                            'enclosure' => array(
                                array(
                                    'url' => $productImage,
                                    'type' => 'image/jpeg',
                                    'length' => '150'
                            ))
                        );

                        array_push($entries, $entry);
                    }
                }
            }
        }


        $configTitle = Mage::getStoreConfig('rssfeed/getaways/feedtitle');
        if ($configTitle == '') {
            $configTitle = Mage::getStoreConfig('general/store_information/name').' Feeds';
        }
        $configLink = Mage::getStoreConfig('rssfeed/rssfeed/link');
        if ($configLink == '') {
            $configLink = Mage::getBaseUrl();
        }
        // Create the RSS array
        $rss = array(
            'title' => $configTitle,
            'link' => $configLink,
            'language' => $lang,
            'charset' => 'UTF-8',
            'entries' => $entries
        );

        // Import the array
        $feed = Zend_Feed::importArray($rss, 'rss');

        // Write the feed to a variable
        $rssFeed = $feed->saveXML();


        $phpSelf = $_SERVER['SCRIPT_FILENAME'];
        $link = explode('index.php', $phpSelf);

        $url = $link[0];

        $file = array();
        if (isset($url)) {
            $file['dom'] = $url . '/media/rss/getaways/' . $storeId . '.xml';
        }
        $baseUrl = Mage::getBaseUrl();
        $baseUrl = explode('index.php', $baseUrl);
        $file['download'] = $baseUrl[0] . 'media/rss/getaways/' . $storeId . '.xml';
        // Write the feed to a file residing in /media/rss/active/
        $fh = fopen($file['dom'], "w");
        fwrite($fh, $rssFeed);
        fclose($fh);
        return $file;
    }

}