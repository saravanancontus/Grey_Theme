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
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml customers wishlist grid item action renderer for few action controls in one cell
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_Sales_Order_Grid_Renderer_Giftorderfrom extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    /**
     * Renders column
     *
     * @param  Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
       

        
        $tprefix = (string) Mage::getConfig()->getTablePrefix();
        $resource = Mage::getSingleton('core/resource');
        $currentdeal = $resource->getConnection('core_write');
        $read = $resource->getConnection('catalog_read');
        $orderTable = $resource->getTableName('sales/order');
        $orderItemTable = $resource->getTableName('sales/order_item');
        $postObject = new Varien_Object();
        $resultGiftMessage = array();
        $giftMessage = $read->fetchALL("Select gift_message_id,sender   from " . $tprefix . "gift_message  where order_id =" .$row->getId(). "");
        $resultGiftMessage = '';
        if($giftMessage[0][gift_message_id] != '')
        {
            $resultGiftMessage = ucfirst($giftMessage[0][sender]);
            
        }else
        {
             $resultGiftMessage = '';
        }
		
		return $resultGiftMessage;
        
    }

    /**
     * Render single action as link html
     *
     * @param  array $action
     * @param  Varien_Object $row
     * @return string
     */
    protected function _toLinkHtml($action, Varien_Object $row)
    {
        $product = $row->getProduct();

        if (isset($action['process']) && $action['process'] == 'configurable') {
            if ($product->canConfigure()) {
                $style = '';
                $onClick = sprintf('onclick="return %s.configureItem(%s)"', $action['control_object'], $row->getId());
            } else {
                $style = 'style="color: #CCC;"';
                $onClick = '';
            }

            return sprintf('<a href="%s" %s %s>%s</a>', $action['url'], $style, $onClick, $action['caption']);
        } else {
            return parent::_toLinkHtml($action, $row);
        }
    }
}