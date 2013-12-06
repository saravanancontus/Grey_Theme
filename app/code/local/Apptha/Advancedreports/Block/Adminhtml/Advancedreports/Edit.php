<?php

class Apptha_Advancedreports_Block_Adminhtml_Advancedreports_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
   
   

    public function __construct()
    {
        parent::__construct();

        $this->_removeButton('save');
        $this->_removeButton('reset');
        $this->_removeButton('back');
         $this->_removeButton('delete');

     
        $this->_objectId = 'id';
        $this->_blockGroup = 'advancedreports';
        $this->_controller = 'adminhtml_advancedreports';



        /*

        $this->_updateButton('save', 'label', Mage::helper('advancedreports')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('advancedreports')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);


    

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('advancedreports_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'advancedreports_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'advancedreports_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
         
         */
    }

    public function getHeaderText()
    {
        
        /*

        if( Mage::registry('advancedreports_data') && Mage::registry('advancedreports_data')->getId() ) {
            return Mage::helper('advancedreports')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('advancedreports_data')->getTitle()));
        } else {
            return Mage::helper('advancedreports')->__('Add Item');
        }
         */
    }



}