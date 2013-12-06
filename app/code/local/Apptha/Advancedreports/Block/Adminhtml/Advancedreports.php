<?php
class Apptha_Advancedreports_Block_Adminhtml_Advancedreports extends Mage_Adminhtml_Block_Widget_Grid_Container
{


     public function __construct() {
        $this->_controller = 'adminhtml_advancedreports';
        $this->_blockGroup = 'advancedreports';
        $this->_headerText = Mage::helper('advancedreports')->__('Advanced Reviews');
        parent::__construct();
        $this->_removeButton('add');
    }

    /*

  public function __construct()
  {
    $this->_controller = 'adminhtml_advancedreports';
    $this->_blockGroup = 'advancedreports';
    $this->_headerText = Mage::helper('advancedreports')->__('Advanced Reviews');
    $this->_addButtonLabel = Mage::helper('advancedreports')->__('Add Item');
    parent::__construct();
  }

     
     */
}