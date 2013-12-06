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
class Contus_Background_Model_Colors extends Varien_Object
{
     public function toOptionArray()
    {

        return array(
            array('value' => 'default', 'label'=>Mage::helper('adminhtml')->__('Default')),
            array('value' => 'black', 'label'=>Mage::helper('adminhtml')->__('Black')),
            array('value' => 'blue', 'label'=>Mage::helper('adminhtml')->__('Blue')),
            array('value' => 'brown', 'label'=>Mage::helper('adminhtml')->__('Brown')),
            array('value' => 'gray', 'label'=>Mage::helper('adminhtml')->__('Gray')),
            array('value' => 'green', 'label'=>Mage::helper('adminhtml')->__('Green')),
            array('value' => 'orange', 'label'=>Mage::helper('adminhtml')->__('Orange')),
            array('value' => 'orange', 'label'=>Mage::helper('adminhtml')->__('Purple')),
            array('value' => 'red', 'label'=>Mage::helper('adminhtml')->__('Red')),
            array('value' => 'violet', 'label'=>Mage::helper('adminhtml')->__('Violet')),
            array('value' => 'yellow', 'label'=>Mage::helper('adminhtml')->__('Yellow')),
        );
    }

}