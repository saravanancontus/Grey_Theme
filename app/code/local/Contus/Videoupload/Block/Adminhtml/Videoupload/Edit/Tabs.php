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

class Contus_Videoupload_Block_Adminhtml_Videoupload_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('videoupload_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('videoupload')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('videoupload')->__('Item Information'),
          'title'     => Mage::helper('videoupload')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('videoupload/adminhtml_videoupload_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}