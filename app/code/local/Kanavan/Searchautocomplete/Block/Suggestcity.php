<?php

class Kanavan_Searchautocomplete_Block_Suggestcity extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getSearchautocomplete() {
        if (!$this->hasData('searchautocomplete')) {
            $this->setData('searchautocomplete', Mage::registry('searchautocomplete'));
        }
        return $this->getData('searchautocomplete');
    }

    public function getSuggestProducts() {
        $select = "";
        $cities = array();
        $attributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'dealcity');
        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $attributeId); // get the cities attribute id 548

        foreach ($attribute->getSource()->getAllOptions(true, true) as $option) {
            $value = $option['value'];

            if ($value != '') {
                $cities[$value] = $option['label'];
                $select .= "<option value='" . $option['value'] . "'>" . $option['label'] . "</option>";
                $cityUrl = Mage::getModel('deal/deal')->getCityUrl($value);
                $city[$option['label']] = $cityUrl;
            }
        }
        Mage::getSingleton('core/session')->setCityUrl($city);
        $final = array_unique($cities);
        return $final;
    }

    public function enabledSuggest() {
        return Mage::getStoreConfig('searchautocomplete/suggest/enable');
    }

    public function enabledPreview() {
        return Mage::getStoreConfig('searchautocomplete/preview/enable');
    }

    public function getImageWidth() {
        return Mage::getStoreConfig('searchautocomplete/preview/image_width');
    }

    public function getImageHeight() {
        return Mage::getStoreConfig('searchautocomplete/preview/image_height');
    }

    public function getEffect() {
        return Mage::getStoreConfig('searchautocomplete/settings/effect');
    }

    public function getPreviewBackground() {
        return Mage::getStoreConfig('searchautocomplete/preview/background');
    }

    public function getSuggestBackground() {
        return Mage::getStoreConfig('searchautocomplete/suggest/background');
    }

    public function getSuggestColor() {
        return Mage::getStoreConfig('searchautocomplete/suggest/suggest_color');
    }

    public function getSuggestCountColor() {
        return Mage::getStoreConfig('searchautocomplete/suggest/count_color');
    }

    public function getBorderColor() {
        return Mage::getStoreConfig('searchautocomplete/settings/border_color');
    }

    public function getBorderWidth() {
        return Mage::getStoreConfig('searchautocomplete/settings/border_width');
    }

    public function isShowImage() {
        return Mage::getStoreConfig('searchautocomplete/preview/show_image');
    }

    public function isShowName() {
        return Mage::getStoreConfig('searchautocomplete/preview/show_name');
    }

    public function getProductNameColor() {
        return Mage::getStoreConfig('searchautocomplete/preview/pro_title_color');
    }

    public function getProductDescriptionColor() {
        return Mage::getStoreConfig('searchautocomplete/preview/pro_description_color');
    }

    public function isShowDescription() {
        return Mage::getStoreConfig('searchautocomplete/preview/show_description');
    }

    public function getNumDescriptionChar() {
        return Mage::getStoreConfig('searchautocomplete/preview/num_char_description');
    }

    public function getImageBorderWidth() {
        return Mage::getStoreConfig('searchautocomplete/preview/image_border_width');
    }

    public function getImageBorderColor() {
        return Mage::getStoreConfig('searchautocomplete/preview/image_border_color');
    }

    public function getHoverBackground() {
        return Mage::getStoreConfig('searchautocomplete/settings/hover_background');
    }

}
