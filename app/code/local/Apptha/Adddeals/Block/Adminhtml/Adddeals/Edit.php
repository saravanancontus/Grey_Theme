<?php

class Apptha_Adddeals_Block_Adminhtml_Adddeals_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'adddeals';
        $this->_controller = 'adminhtml_adddeals';
        
       $this->_removeButton('reset');
$this->_removeButton('save');
$this->_removeButton('back');
       

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('adddeals_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'adddeals_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'adddeals_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
    	
        if( Mage::registry('adddeals_data') && Mage::registry('adddeals_data')->getId() ) {
            return Mage::helper('adddeals')->__("Edit Deal");
        } else {
            return Mage::helper('adddeals')->__('Add New Deal');
        }
    }
}