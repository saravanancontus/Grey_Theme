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

class Mage_Adminhtml_Block_Catalog_Product_Grid_Renderer_Dealcategory extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
       
        $pid = $row->getId(); 
		   $model = Mage::getModel('catalog/product')->load($pid);
		     $categoryIds = $model->getCategoryIds();
		    
		     if(!empty($categoryIds))
		     {
		     	foreach ( $categoryIds as $categoryId) {
		     		
		     		$category_collection = Mage::getModel('catalog/category')->load($categoryId);
		     		$categoryname[] = $category_collection->getName();
		     		
		     	}
		     }
		     if(!empty($categoryname))
		     {
		     $categoryName = implode(',',$categoryname);	
		     }
		     
            
		     return $categoryName;
        
       
    }
}
