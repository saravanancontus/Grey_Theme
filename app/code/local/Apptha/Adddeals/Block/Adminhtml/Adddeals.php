<?php
class Apptha_Adddeals_Block_Adminhtml_Adddeals extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_adddeals';
    $this->_blockGroup = 'adddeals';
    $this->_headerText = Mage::helper('adddeals')->__('Manage Deals');
    $this->_addButtonLabel = Mage::helper('adddeals')->__('Add New Deal');
    parent::__construct();
  }
}