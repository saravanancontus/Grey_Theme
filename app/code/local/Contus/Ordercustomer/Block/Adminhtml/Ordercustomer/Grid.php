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
class Contus_Ordercustomer_Block_Adminhtml_Ordercustomer_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('ordercustomerGrid');
      $this->setDefaultSort('ordercustomer_id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);

      //$this->setUseAjax(true);
      //$this->setVarNameFilter('ordercustomer_filter');
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('ordercustomer/ordercustomer')->getCollection();

      // Fetching the product name of that purticular order
        $tprefix = (string)Mage::getConfig()->getTablePrefix();
        $tableName = $tprefix.'sales_flat_order_grid';
        $collection->getSelect()->joinLeft(array('order_grid' => $tableName),'order_grid.entity_id = main_table.order_id',
                                       array('increment_id'));

      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('ordercustomer_id', array(
          'header'    => Mage::helper('ordercustomer')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'ordercustomer_id',
      ));

      $this->addColumn('increment_id', array(
			'header'    => Mage::helper('ordercustomer')->__('Order Id'),
			'width'     => '120px',
			'index'     => 'increment_id',
      ));
	  
      $this->addColumn('customer_name', array(
          'header'    => Mage::helper('ordercustomer')->__('Customer Name'),
          'align'     =>'left',
          'index'     => 'customer_name',
      ));

	  
       $this->addColumn('product_name', array(
          'header'    => Mage::helper('ordercustomer')->__('Product name'),
          'align'     =>'left',
          'index'     => 'product_name',
      ));
	  
	  $this->addColumn('quantity', array(
          'header'    => Mage::helper('ordercustomer')->__('Quantity'),
          'align'     =>'left',
              'width'     => '90px',
          'index'     => 'quantity',
      ));
	  
	  $this->addColumn('quantitynumber', array(
          'header'    => Mage::helper('ordercustomer')->__('Coupon Number#'),
          'align'     =>'left',
          'index'     => 'quantitynumber',
      ));
	  
	  $this->addColumn('uniqueid', array(
          'header'    => Mage::helper('ordercustomer')->__('Coupon Code'),
          'align'     =>'left',
          'index'     => 'uniqueid',
      ));
	  
	   $this->addColumn('created_time', array(
          'header'    => Mage::helper('ordercustomer')->__('Created Date'),
          'align'     =>'left',
          'type' => 'datetime',
               'width'     => '170px',
          'index'     => 'created_time',
      ));
	  

    $this->addColumn('couponstatus', array(
          'header'    => Mage::helper('ordercustomer')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'couponstatus',
          'type'      => 'options',
          'options'   => array(
              1 => 'Not Used',
              2 => 'Used',
          ),
//        'filter'    => false
      ));
	  

		
		$this->addExportType('*/*/exportCsv', Mage::helper('ordercustomer')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('ordercustomer')->__('XML'));
	  
      return parent::_prepareColumns();
  }

  protected function _prepareMassaction() {
        $this->setMassactionIdField('ordercustomer_id');
        $this->getMassactionBlock()->setFormFieldName('ordercustomer');

        $this->getMassactionBlock()->addItem('delete', array(
                'label'=> Mage::helper('catalog')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm' => Mage::helper('catalog')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('ordercustomer/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
                'label'=> Mage::helper('catalog')->__('Change status'),
                'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
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

        $this->getMassactionBlock()->addItem('Send Coupon', array(
                'label'=> Mage::helper('catalog')->__('Send Coupon'),
                'url'  => $this->getUrl('*/*/massSendCoupon'),
                'confirm' => Mage::helper('catalog')->__('Are you sure?')
        ));
        
        return $this;
    }

  public function getRowUrl($row)
  {
      //return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}