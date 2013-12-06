<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Used in creating options for Yes|No config value selection
 *
 */
class Mage_Adminhtml_Model_System_Config_Source_skin
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => skin_fresh_blue, 'label'=>Mage::helper('adminhtml')->__('Blue')),
            array('value' => skin_fresh_white, 'label'=>Mage::helper('adminhtml')->__('White')),
            array('value' => skin_fresh_yellow, 'label'=>Mage::helper('adminhtml')->__('Yellow')),
            array('value' => skin_neat_fresh_yellow, 'label'=>Mage::helper('adminhtml')->__('Fresh Yellow')),
            array('value' => skin_neat_fresh_orange, 'label'=>Mage::helper('adminhtml')->__('Fresh Orange')),

           
        );
    }

}
