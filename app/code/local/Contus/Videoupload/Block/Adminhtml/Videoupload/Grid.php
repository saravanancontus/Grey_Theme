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

/*
 * Contus Support Pvt Ltd.
 * created by Vasanth on nov 02 2010.
 * vasanth@contus.in
 * In this page we can Show the video Details.
 */
?>
<?php

class Contus_Videoupload_Block_Adminhtml_Videoupload_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('videouploadGrid');
      $this->setDefaultSort('videoupload_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('videoupload/videoupload')->getCollection();

      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {

        $model = Mage::getModel('catalog/product'); //getting product model
        $_product = $model->load('entity_id');
        $products = $_product->getName();

      $this->addColumn('videoupload_id', array(
          'header'    => Mage::helper('videoupload')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'videoupload_id',
      ));

      $this->addColumn('product', array(
          'header'    => Mage::helper('videoupload')->__('Product Name'),
          'align'     =>'left',
          'index'     => 'product',
      ));
     $this->addColumn('title', array(
          'header'    => Mage::helper('videoupload')->__('Video Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));

	  

      $this->addColumn('status', array(
          'header'    => Mage::helper('videoupload')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('videoupload')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('videoupload')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('videoupload')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('videoupload')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('videoupload_id');
        $this->getMassactionBlock()->setFormFieldName('videoupload');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('videoupload')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('videoupload')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('videoupload/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('videoupload')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('videoupload')->__('Status'),
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