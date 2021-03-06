<?php
/**
 * Magento
 *
 * Contus Support Pvt Ltd.
 * created by Vasanth on nov 02 2010.
 * vasanth@contus.in
 */

/**
 * Used in creating options for Logo Align config value selection
 *
 */
class Mage_Adminhtml_Model_System_Config_Source_Align
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' =>TR, 'label'=>Mage::helper('adminhtml')->__('Top Right')),
            array('value' => TL, 'label'=>Mage::helper('adminhtml')->__('Top Left')),
            array('value' => BL, 'label'=>Mage::helper('adminhtml')->__('Bottom Left')),
            array('value' => BR, 'label'=>Mage::helper('adminhtml')->__('Bottom Right')),

        );
    }

}
