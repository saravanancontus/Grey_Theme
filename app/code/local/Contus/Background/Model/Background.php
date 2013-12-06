<?php

/**
 * Contus Support Interactive.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file GCLONE-LICENSE.txt.
 *
 */
class Contus_Background_Model_Background extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('background/background');
    }

    public function getBackground() {
        $request = Mage::app()->getFrontController()->getRequest();
        $actionName = $request->getActionName();
        if ($actionName != 'subscribepage') {
            $resource = Mage::getSingleton('core/resource');
            $read = $resource->getConnection('catalog_read');
            $background = $resource->getTableName('background');
            $storeId = Mage::app()->getStore()->getId();
            $currentCity = Mage::getSingleton('core/session')->getCity();
            $select = $read->select()
                            ->from(array('cp' => $background))
                            ->where('cp.city = ?', $currentCity)
                            ->where('cp.status = ?', '1')
                            ->where('cp.store = ?', $storeId);

            $background_image_array = $read->fetchAll($select);
            if (isset($background_image_array[0]['filename'])) {
                $baseUrl = explode('index.php', Mage::getBaseUrl());
                $backgroundUrl = $baseUrl[0] . 'media/' . $background_image_array[0]['filename'];
                $background_image = "background-image:url('" . $backgroundUrl . "');background-repeat:no-repeat; background-attachment: fixed; background-position: 50% 0%;";
                Mage::getSingleton('core/session')->setBackground('yes');
            } else {
                Mage::getSingleton('core/session')->setBackground('no');
                $background_image = "";
            }
        }
        else{
            $background_image = "";
        }
        return $background_image;
    }

}