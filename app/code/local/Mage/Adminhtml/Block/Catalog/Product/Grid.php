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
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Adminhtml customer grid block
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Adminhtml_Block_Catalog_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid {

public function __construct() {
parent::__construct();
$this->setId('productGrid');
$this->setDefaultSort('entity_id');
$this->setDefaultDir('desc');
$this->setSaveParametersInSession(true);
$this->setUseAjax(true);
$this->setVarNameFilter('product_filter');
}

protected function _getStore() {
$storeId = (int) $this->getRequest()->getParam('store', 0);
return Mage::app()->getStore($storeId);
}

protected function _prepareCollection() {
$store = $this->_getStore();
$collection = Mage::getModel('catalog/product')->getCollection()
//   ->addAttributeToSelect('sku')
->addAttributeToSelect('special_from_date')
->addAttributeToSelect('special_to_date')
->addAttributeToSelect('special_price')
->addAttributeToSelect('deal_status')
->addAttributeToSelect('time')
->addAttributeToSelect('target_number')
->addAttributeToSelect('dealcity')
->addAttributeToSelect('deal_success')
->addAttributeToSelect('dealcategory')
->addAttributeToSelect('merchant')
->addAttributeToSelect('customersite')
->addAttributeToSelect('name');

/**
 * To filter results based on the dashboard
 * contus
 */
$filter = $this->getRequest()->getParam('filter');
if (isset($filter)) {
if ($filter == 'mainActive') {
$catId = 3;
$dealStatus = 5;
} else if ($filter == 'mainUpcoming') {
$catId = 3;
$dealStatus = 6;
} else if ($filter == 'mainPast') {
$catId = 3;
$dealStatus[0] = 3;
$dealStatus[1] = 4;
} else if ($filter == 'sideActive') {
$catId = 4;
$dealStatus = 5;
} else if ($filter == 'sideUpcoming') {
$catId = 4;
$dealStatus = 6;
} else if ($filter == 'sidePast') {
$catId = 4;
$dealStatus[0] = 3;
$dealStatus[1] = 4;
}
$collection->addCategoryFilter(Mage::getModel('catalog/category')->load($catId));
$collection->addAttributeToFilter('deal_status', array('and' => $dealStatus));
$city = $this->getRequest()->getParam('dealcity');
if (isset($city)) {
$collection->addAttributeToFilter('dealcity', array('and' => $city));
}
}
$adminuser = Mage::getSingleton('admin/session')->getUser();
$roleId = implode('', $adminuser->getRoles());
$userId = $adminuser->getId();
$roleName = Mage::getModel('admin/roles')->load($roleId)->getRoleName();
if (strtolower($roleName) != 'administrators') {
$collection->addAttributeToFilter('merchant', array('and' => $userId)); //for every merchants
}
//   ->addAttributeToSelect('attribute_set_id')
//   ->addAttributeToSelect('type_id')
//            ->joinField('qty',
//                'cataloginventory/stock_item',
//                'qty',
//                'product_id=entity_id',
//                '{{table}}.stock_id=1',
//                'left');

if ($store->getId()) {
//$collection->setStoreId($store->getId());
$collection->addStoreFilter($store);
$collection->joinAttribute('custom_name', 'catalog_product/name', 'entity_id', null, 'inner', $store->getId());
$collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', $store->getId());
$collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner', $store->getId());
$collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $store->getId());
} else {
$collection->addAttributeToSelect('price');
$collection->addAttributeToSelect('status');
// $collection->addAttributeToSelect('visibility');
}

$this->setCollection($collection);

parent::_prepareCollection();
$this->getCollection()->addWebsiteNamesToResult();
return $this;
}

protected function _addColumnFilterToCollection($column) {
if ($this->getCollection()) {
if ($column->getId() == 'websites') {
$this->getCollection()->joinField('websites',
 'catalog/product_website',
 'website_id',
 'product_id=entity_id',
 null,
 'left');
}
}
return parent::_addColumnFilterToCollection($column);
}

protected function _prepareColumns() {

$this->addColumn('name',
 array(
'header' => Mage::helper('catalog')->__('Deal Name'),
 'index' => 'name',
));
$this->addColumn('deal_category',
 array(
'header' => Mage::helper('catalog')->__('Deal Category'),
 'width' => '10px',
 'sortable' => false,
 'filter' => false,
 'renderer' => 'adminhtml/catalog_product_grid_renderer_dealcategory',
));
$this->addColumn('dealcity',
 array(
'header' => Mage::helper('catalog')->__('City'),
 'width' => '100px',
 'index' => 'dealcity',
 'type' => 'options',
 'options' => Mage::getSingleton('catalog/product_cities')->getOptionArray(),
 'sortable' => false,
));
$this->addColumn('target_number',
 array(
'header' => Mage::helper('catalog')->__('Target'),
 'index' => 'target_number',
));
$this->addColumn('deal_purchased',
 array(
'header' => Mage::helper('catalog')->__('Purchased'),
 'width' => '10px',
 'sortable' => false,
 'filter' => false,
 'renderer' => 'adminhtml/catalog_product_grid_renderer_dealpurchased',
));
$this->addColumn('time_remaining',
 array(
'header' => Mage::helper('catalog')->__('Time<br>Remaining'),
 'index' => 'entity_id',
 'type' => 'options',
 'options' => Mage::getSingleton('catalog/product_timeremaining')->getOptionArray(),
 'sortable' => false,
 'filter' => false,
));
$store = $this->_getStore();
if ($store->getId()) {
$this->addColumn('custom_name',
 array(
'header' => Mage::helper('catalog')->__('Name In %s', $store->getName()),
 'index' => 'custom_name',
));
}


$sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
->load()
->toOptionHash();

$this->addColumn('special_from_date',
 array(
'header' => Mage::helper('catalog')->__('From Date'),
 'width' => '70px',
 'type' => 'date',
 'index' => 'special_from_date',
));

$this->addColumn('special_to_date',
 array(
'header' => Mage::helper('catalog')->__('Expiry Date'),
 'width' => '80px',
 'type' => 'date',
 'index' => 'special_to_date',
));

$this->addColumn('time',
 array(
'header' => Mage::helper('catalog')->__('Expiry<br>Time'),
 'filter' => false,
 'width' => '60px',
 'index' => 'time',
));



$store = $this->_getStore();
$this->addColumn('price',
 array(
'header' => Mage::helper('catalog')->__('Special Price'),
 'type' => 'price',
 'currency_code' => $store->getBaseCurrency()->getCode(),
 'index' => 'special_price',
));

/* contus */
$this->addColumn('status',
 array(
'header' => Mage::helper('catalog')->__('Current Deal'),
 'width' => '10px',
 'index' => 'status',
 'type' => 'options',
 'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
));


//        $this->addColumn('deal_status',
//                array(
//                'header'=> Mage::helper('catalog')->__('Deal Status'),
//                'width' => '70px',
//                'index' => 'deal_status',
//                'type'  => 'options',
//                'options' => Mage::getSingleton('catalog/product_dealstatus')->getOptionArray(),
//        ));


if (!Mage::app()->isSingleStoreMode()) {
$this->addColumn('websites',
 array(
'header' => Mage::helper('catalog')->__('Websites'),
 'width' => '100px',
 'sortable' => false,
 'index' => 'websites',
 'type' => 'options',
 'options' => Mage::getModel('core/website')->getCollection()->toOptionHash(),
));
}

$this->addColumn('action',
 array(
'header' => Mage::helper('catalog')->__('Action'),
 'width' => '50px',
 'type' => 'action',
 'getter' => 'getId',
 'actions' => array(
array(
'caption' => Mage::helper('catalog')->__('Edit'),
 'url' => array(
'base' => '*/*/edit',
 'params' => array('store' => $this->getRequest()->getParam('store'))
),
 'field' => 'id'
)
),
 'filter' => false,
 'sortable' => false,
 'index' => 'stores',
));

$this->addColumn('page_actions', array(
'header' => Mage::helper('catalog')->__('Preview'),
 'width' => '10px',
 'sortable' => false,
 'filter' => false,
 'renderer' => 'adminhtml/catalog_product_grid_renderer_action',
));

$this->addColumn('deal_report', array(
'header' => Mage::helper('catalog')->__('Deal Report'),
 'width' => '10px',
 'sortable' => false,
 'filter' => false,
 'renderer' => 'adminhtml/catalog_product_grid_renderer_report',
));

$this->addColumn('payment_actions', array(
'header' => Mage::helper('catalog')->__('Payment Status'),
 'width' => '10px',
 'sortable' => false,
 'filter' => false,
 'renderer' => 'adminhtml/catalog_product_grid_renderer_payment',
));
//  $this->addRssList('rss/catalog/notifystock', Mage::helper('catalog')->__('Notify Low Stock RSS'));



return parent::_prepareColumns();
}

protected function _prepareMassaction() {
$this->setMassactionIdField('entity_id');
$this->getMassactionBlock()->setFormFieldName('product');

$this->getMassactionBlock()->addItem('delete', array(
'label' => Mage::helper('catalog')->__('Delete'),
 'url' => $this->getUrl('*/*/massDelete'),
 'confirm' => Mage::helper('catalog')->__('Are you sure?')
));

$statuses = Mage::getSingleton('catalog/product_status')->getOptionArray();

array_unshift($statuses, array('label' => '', 'value' => ''));
$this->getMassactionBlock()->addItem('status', array(
'label' => Mage::helper('catalog')->__('Change status'),
 'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
 'additional' => array(
'visibility' => array(
'name' => 'status',
 'type' => 'select',
 'class' => 'required-entry',
 'label' => Mage::helper('catalog')->__('Status'),
 'values' => $statuses
)
)
));

if (Mage::getSingleton('admin/session')->isAllowed('catalog/update_attributes')) {
$this->getMassactionBlock()->addItem('attributes', array(
'label' => Mage::helper('catalog')->__('Update attributes'),
 'url' => $this->getUrl('*/catalog_product_action_attribute/edit', array('_current' => true))
));
}

return $this;
}

public function getGridUrl() {
return $this->getUrl('*/*/grid', array('_current' => true));
}

public function getRowUrl($row) {
return $this->getUrl('*/*/edit', array(
'store' => $this->getRequest()->getParam('store'),
 'id' => $row->getId())
);
}

}
