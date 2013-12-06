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

class Contus_Share_Block_Adminhtml_Share_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('shareGrid');
      $this->setDefaultSort('share_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('share/share')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('share_id', array(
          'header'    => Mage::helper('share')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'share_id',
      ));

      $this->addColumn('facebook', array(
          'header'    => Mage::helper('share')->__('Facebook'),
          'align'     =>'left',
          'index'     => 'facebook',
      ));

      $this->addColumn('twitter', array(
          'header'    => Mage::helper('share')->__('Twitter'),
          'align'     =>'left',
          'index'     => 'twitter',
      ));

      $this->addColumn('linkedin', array(
          'header'    => Mage::helper('share')->__('Linkedin'),
          'align'     =>'left',
          'index'     => 'linkedin',
      ));
	  
      $this->addColumn('facebookfans', array(
          'header'    => Mage::helper('share')->__('Facebook Fans Id'),
          'align'     =>'left',
          'index'     => 'facebookfans',
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('share')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('share')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
//
//		$this->addExportType('*/*/exportCsv', Mage::helper('share')->__('CSV'));
//		$this->addExportType('*/*/exportXml', Mage::helper('share')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
//        $this->setMassactionIdField('share_id');
//        $this->getMassactionBlock()->setFormFieldName('share');
//
//        $this->getMassactionBlock()->addItem('delete', array(
//             'label'    => Mage::helper('share')->__('Delete'),
//             'url'      => $this->getUrl('*/*/massDelete'),
//             'confirm'  => Mage::helper('share')->__('Are you sure?')
//        ));
//
//        $statuses = Mage::getSingleton('share/status')->getOptionArray();
//
//        array_unshift($statuses, array('label'=>'', 'value'=>''));
//        $this->getMassactionBlock()->addItem('status', array(
//             'label'=> Mage::helper('share')->__('Change status'),
//             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
//             'additional' => array(
//                    'visibility' => array(
//                         'name' => 'status',
//                         'type' => 'select',
//                         'class' => 'required-entry',
//                         'label' => Mage::helper('share')->__('Status'),
//                         'values' => $statuses
//                     )
//             )
//        ));
//        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}