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

class Mage_Adminhtml_Block_Catalog_Product_Grid_Renderer_Dealpurchased extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $urlModel = Mage::getModel('core/url')->setStore($row->getData('_first_store_id'));
        $pid = $row->getId(); 
		$resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('catalog_read');
        $orderTable = $resource->getTableName('sales/order');
        $orderItemTable = $resource->getTableName('sales/order_item');
        $dealstatus[0] = "processing";
        $dealstatus[1] = "complete";
       
       
        
            
            $select = $read->select()
            ->from(array('cp'=>$orderTable),array( 'totalcount'  => 'sum(cp.total_qty_ordered)'))
            ->join(array('pei'=>$orderItemTable), 'pei.order_id=cp.entity_id', array())
            ->where('cp.status in (?)', $dealstatus)
            ->where('pei.product_id in (?)', $pid);
            $orders_list = $read->fetchAll($select);
            $OrderCount = floor($orders_list[0]['totalcount']);
            if($OrderCount>0)
            {
                $count = $OrderCount;
            }
            else
            {
                $count = '0';
            }
       
        return $count;
        
       
    }
}
