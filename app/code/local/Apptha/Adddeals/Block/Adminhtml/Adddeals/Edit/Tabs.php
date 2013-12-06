<?php

class Apptha_Adddeals_Block_Adminhtml_Adddeals_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('adddeals_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('adddeals')->__('Deal Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('adddeals')->__('Deal Information'),
          'title'     => Mage::helper('adddeals')->__('Deal Information'),
          'content'   => $this->getLayout()->createBlock('adddeals/adminhtml_adddeals_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}