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
class Contus_Background_Block_Adminhtml_Background_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('backgroundGrid');
        $this->setDefaultSort('background_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('background/background')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('background_id', array(
            'header' => Mage::helper('background')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'background_id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('background')->__('Title'),
            'align' => 'left',
            'index' => 'title',
        ));

        $cities = array();
        $attributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product', 'dealcity');

        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $attributeId); // get the cities attribute id 548
//// to get the multiple cities in pages(drop down)
        foreach ($attribute->getSource()->getAllOptions(true, true) as $option) {
            $value = $option['value'];
            if ($value != '') {
                $cities[$value] = $option['label'];
            }
        }

        $this->addColumn('city', array(
            'header' => Mage::helper('background')->__('city'),
            'width' => '150px',
            'index' => 'city',
            'type' => 'options',
            'options' => $cities,
        ));

        $storeView = array();
        foreach (Mage::app()->getWebsites() as $website) {
            foreach ($website->getGroups() as $group) {
                $stores = $group->getStores();
                foreach ($stores as $store) {
                    //$store is a store object
                    $storeView[$store->getStoreId()] = $store->getName();
                }
            }
        }

        $this->addColumn('store', array(
            'header' => Mage::helper('background')->__('store'),
            'width' => '150px',
            'index' => 'store',
            'type' => 'options',
            'options' => $storeView,
        ));




        $this->addColumn('status', array(
            'header' => Mage::helper('background')->__('Status'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'status',
            'type' => 'options',
            'options' => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
        ));

        $this->addColumn('action',
                array(
                    'header' => Mage::helper('background')->__('Action'),
                    'width' => '100',
                    'type' => 'action',
                    'getter' => 'getId',
                    'actions' => array(
                        array(
                            'caption' => Mage::helper('background')->__('Edit'),
                            'url' => array('base' => '*/*/edit'),
                            'field' => 'id'
                        )
                    ),
                    'filter' => false,
                    'sortable' => false,
                    'index' => 'stores',
                    'is_system' => true,
        ));

//		$this->addExportType('*/*/exportCsv', Mage::helper('background')->__('CSV'));
//		$this->addExportType('*/*/exportXml', Mage::helper('background')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('background_id');
        $this->getMassactionBlock()->setFormFieldName('background');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('background')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('background')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('background/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => Mage::helper('background')->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('background')->__('Status'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}