<?php

class Apptha_Adddeals_Model_Adddeals extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('adddeals/adddeals');
        global $l, $style;
    }

    public function getProductCities() {

        $cityAttributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'dealcity');
        return $city_attribute->getSource()->getAllOptions(true, true);
    }

    public function getProductCategories() {

        $categories = Mage::app()->getWebsite(true)->getDefaultStore()->getRootCategoryId();
        $category = Mage::getModel('catalog/category')->load($categories);
        $categorychild = $category->getChildren();


        $categoryList = explode(",", $categorychild);
        $i = 0;
        foreach ($categoryList as $categoryChildId) {
            $category = Mage::getModel('catalog/category')->load($categoryChildId);
            $rootCategoryDetails[] = $this->getProductChild($category->getId());

            $rootCategoryDetails [$i]['id'] = $category->getId();
            $rootCategoryDetails[$i]['name'] = $category->getName();
            $i++;
        }

        return $rootCategoryDetails;
    }

    public function getVideoProduct($id) {
        /** @var $coreResource Mage_Core_Model_Resource */
        $coreResource = Mage::getSingleton('core/resource');

        /** @var $conn Varien_Db_Adapter_Pdo_Mysql */
        $conn = $coreResource->getConnection('core_read');

        $select = $conn->select()
                ->from(array('p' => $coreResource->getTableName('videoupload')), new Zend_Db_Expr('`videoupload_id`, `category_id`, `entity_id`, `video_id`, `video_type`, `videoname`, `title`, `thumname`, `product`, `status`, `created_time`, `update_time`'))
                ->where('p.entity_id = ?', $id);


        $videoDetails = $conn->fetchAll($select);
        return $videoDetails;
    }

    public function getProductChild($categoryID) {
        $Childcategory = Mage::getModel('catalog/category')->load($categoryID);
        $categorychild = $Childcategory->getChildren();


        if ($categorychild != '') {
            $categorychildArray = explode(',', $categorychild);
            $i = 0;
            foreach ($categorychildArray as $categoryChildId) {
                $category_sub = Mage::getModel('catalog/category')->load($categoryChildId);
                $category_id[$i]['id'] = $category_sub->getId();
                $category_id[$i]['name'] = $category_sub->getName();
                $category_id[] = $this->getProductChild($category_sub->getId());
                $i++;
            }
        }

        return $category_id;
    }

    public function getChildCategory($categoryID, $id, $l, $style) {


        if ($id != '') {

            $model = Mage::getModel('catalog/product')->load($id);
            $categoryIds = $model->getCategoryIds();
        }


        $Childcategory = Mage::getModel('catalog/category')->load($categoryID);
        $categorychild = $Childcategory->getChildren();


        if ($categorychild != '') {
            $categorychildArray = explode(',', $categorychild);

            foreach ($categorychildArray as $categoryChildId) {

                $category_sub = Mage::getModel('catalog/category')->load($categoryChildId);


                $selected = '';
                if (in_array($category_sub->getId(), $categoryIds)) {
                    $selected = 'checked = "checked"';
                }
                
                if ($l == 0) {
                    
                    $style = 10;
                    $childcategories_class = "margin:0 0 0 " . $style . "px;";
                } 
                
                if ($l > 0) {
                   
                   
                   $style = $style + 10;
                    $childcategories_class = "margin:0 0 0 " . $style . "px;";
                }
               
                $subcategory .= '<li><input id="' . $category_sub->getId() . '" type="checkbox" style = "' . $childcategories_class . '" onClick ="saveCategory(' . $category_sub->getId() . ')"
                                    name="dealCategories" ' . $selected . ' name= "' . $category_sub->getId() . '"  > <label>' . $category_sub->getName() . '</label></li> ';
                $Childcategory = Mage::getModel('catalog/category')->load($category_sub->getId());
                $l = $l + 1;
                
                $subcategory .= Mage::getModel('adddeals/adddeals')->getChildCategory($category_sub->getId(), '', $l, $style);
               
            }
        }

        return $subcategory;
    }

    public function getProductCategoryId() {

        $categories = Mage::app()->getWebsite(true)->getDefaultStore()->getRootCategoryId();
        $category = Mage::getModel('catalog/category')->load($categories);
        $categorychild = $category->getChildren();
        $categoryList = explode(",", $categorychild);
        return $categoryList;
    }

    public function hd_GetSingleYoutubeVideo($youtube_media) {

        if ($youtube_media == '')
            return;

        $url = 'http://gdata.youtube.com/feeds/api/videos/' . $youtube_media;

        $ytb = $this->hd_ParseYoutubeDetails($this->hd_GetYoutubePage($url));


        return $ytb[0];
    }

    public function hd_ParseYoutubeDetails($ytVideoXML) {

        // Create parser, fill it with xml then delete it

        $yt_xml_parser = xml_parser_create();

        xml_parse_into_struct($yt_xml_parser, $ytVideoXML, $yt_vals);

        xml_parser_free($yt_xml_parser);
        // Init individual entry array and list array

        $yt_video = array();

        $yt_vidlist = array();

        // is_entry tests if an entry is processing

        $is_entry = true;

// is_author tests if an author tag is processing

        $is_author = false;

        foreach ($yt_vals as $yt_elem) {

            // If no entry is being processed and tag is not start of entry, skip tag

            if (!$is_entry && $yt_elem['tag'] != 'ENTRY')
                continue;

            // Processed tag

            switch ($yt_elem['tag']) {
                case 'ENTRY' :
                    if ($yt_elem['type'] == 'open') {

                        $is_entry = true;

                        $yt_video = array();
                    } else {

                        $yt_vidlist[] = $yt_video;

                        $is_entry = false;
                    }
                    break;
                case 'ID' :

                    $yt_video['id'] = substr($yt_elem['value'], -11);

                    $yt_video['link'] = $yt_elem['value'];

                    break;

                case 'PUBLISHED' :

                    $yt_video['published'] = substr($yt_elem['value'], 0, 10) . ' ' . substr($yt_elem['value'], 11, 8);

                    break;
                case 'UPDATED' :

                    $yt_video['updated'] = substr($yt_elem['value'], 0, 10) . ' ' . substr($yt_elem['value'], 11, 8);

                    break;
                case 'MEDIA:TITLE' :

                    $yt_video['title'] = $yt_elem['value'];

                    break;
                case 'MEDIA:KEYWORDS' :

                    $yt_video['tags'] = $yt_elem['value'];

                    break;
                case 'MEDIA:DESCRIPTION' :

                    $yt_video['description'] = $yt_elem['value'];

                    break;
                case 'MEDIA:CATEGORY' :

                    $yt_video['category'] = $yt_elem['value'];

                    break;
                case 'YT:DURATION' :

                    $yt_video['duration'] = $yt_elem['attributes'];

                    break;
                case 'MEDIA:THUMBNAIL' :
                    if ($yt_elem['attributes']['HEIGHT'] == 240) {

                        $yt_video['thumbnail'] = $yt_elem['attributes'];

                        $yt_video['thumbnail_url'] = $yt_elem['attributes']['URL'];
                    }
                    break;
                case 'YT:STATISTICS' :

                    $yt_video['viewed'] = $yt_elem['attributes']['VIEWCOUNT'];

                    break;
                case 'GD:RATING' :

                    $yt_video['rating'] = $yt_elem['attributes'];

                    break;
                case 'AUTHOR' :

                    $is_author = ($yt_elem['type'] == 'open');

                    break;
                case 'NAME' :
                    if ($is_author)
                        $yt_video['author_name'] = $yt_elem['value'];

                    break;
                case 'URI' :
                    if ($is_author)
                        $yt_video['author_uri'] = $yt_elem['value'];

                    break;
                default :
            }
        }

        unset($yt_vals);

        return $yt_vidlist;
    }

    public function hd_GetYoutubePage($url) {

        // Try to use curl first
        if (function_exists('curl_init')) {

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $xml = curl_exec($ch);

            curl_close($ch);
        }
        // If not found, try to use file_get_contents (requires php > 4.3.0 and allow_url_fopen)
        else {
            $xml = @file_get_contents($url);
        }

        return $xml;
    }

    public function getChildCategories($rootCategoryId) {

        foreach ($rootCategoryId as $rootCategory) {
            $Childcategory = Mage::getModel('catalog/category')->load($rootCategory);
            $ChildCategoryName[] = $Childcategory->getName();
        }

        return $ChildCategoryName;
    }

}