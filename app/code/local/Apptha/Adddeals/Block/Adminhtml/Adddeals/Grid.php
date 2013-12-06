<?php

class Apptha_Adddeals_Block_Adminhtml_Adddeals_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('adddealsGrid');
		$this->setDefaultSort('adddeals_id');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getModel('catalog/product')
		->getCollection()
		->addAttributeToSelect('*')
		->addAttributeToSort('created_at', 'desc');
		 



		$adminuser = Mage::getSingleton('admin/session')->getUser();
		$roleId = implode('', $adminuser->getRoles());
		$userId = $adminuser->getId();
		$roleName = Mage::getModel('admin/roles')->load($roleId)->getRoleName();
		if (strtolower($roleName) != 'administrators') {
			$collection->addAttributeToFilter('merchant', array('and' => $userId)); //for every merchants
		}
		 $storeId = (int) $this->getRequest()->getParam('store', 0); 
		$store = Mage::app()->getStore($storeId);
		if ($store->getId()) {

			$collection->addStoreFilter($store);
			$collection->joinAttribute('custom_name', 'catalog_product/name', 'entity_id', null, 'inner', $store->getId());
			$collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', $store->getId());
			$collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', null, 'inner', $store->getId());
			$collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $store->getId());
		} else {
			$collection->addAttributeToSelect('price');
			$collection->addAttributeToSelect('status');
			$this->setCollection($collection);
		}

		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
		 
		$this->addColumn('name',
		array(
		'header' => Mage::helper('catalog')->__('Deal Name'),
		 'index' => 'name',
		));


		$this->addColumn('target_number',
		array(
'header' => Mage::helper('catalog')->__('Target'),
 'index' => 'target_number',
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


		$this->addColumn('special_from_date',
		array(
'header' => Mage::helper('catalog')->__('From Date'),
 'width' => '70px',
 'type' => 'date',
 'index' => 'special_from_date',
		));


		$this->addColumn('special_to_date',
		array(
'header' => Mage::helper('adddeals')->__('Expiry Date'),
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



		$this->addColumn('status',
		array(
'header' => Mage::helper('catalog')->__('Current Deal'),
 'width' => '10px',
 'index' => 'status',
 'type' => 'options',
 'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
		));


		$this->addColumn('deal_purchased',
		array(
'header' => Mage::helper('catalog')->__('Purchased'),
 'width' => '10px',
 'sortable' => false,
 'filter' => false,
 'renderer' => 'adminhtml/catalog_product_grid_renderer_dealpurchased',
		));

		$this->addColumn('deal_report', array(
'header' => Mage::helper('catalog')->__('Deal Report'),
 'width' => '10px',
 'sortable' => false,
 'filter' => false,
 'renderer' => 'adminhtml/catalog_product_grid_renderer_report',
		));



		$this->addColumn('page_actions', array(
'header' => Mage::helper('catalog')->__('Preview'),
 'width' => '10px',
 'sortable' => false,
 'filter' => false,
 'renderer' => 'adminhtml/catalog_product_grid_renderer_action',
		));


		$this->addColumn('payment_actions', array(
'header' => Mage::helper('catalog')->__('Payment Status'),
 'width' => '10px',
 'sortable' => false,
 'filter' => false,
 'renderer' => 'adminhtml/catalog_product_grid_renderer_payment',
		));


		$this->addExportType('*/*/exportCsv', Mage::helper('adddeals')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('adddeals')->__('XML'));
		 
		return parent::_prepareColumns();
	}

	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('adddeals_id');
		$this->getMassactionBlock()->setFormFieldName('adddeals');

		$this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('adddeals')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('adddeals')->__('Are you sure?')
		));

		$statuses = Mage::getSingleton('adddeals/status')->getOptionArray();

		array_unshift($statuses, array('label'=>'', 'value'=>''));
		$this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('adddeals')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('adddeals')->__('Status'),
                         'values' => $statuses
		)
		)
		));
		return $this;
	}

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}

}