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

class Contus_Share_Block_Adminhtml_Share_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'share';
        $this->_controller = 'adminhtml_share';
        
        $this->_updateButton('save', 'label', Mage::helper('share')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('share')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('share_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'share_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'share_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('share_data') && Mage::registry('share_data')->getId() ) {
            return Mage::helper('share')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('share_data')->getTitle()));
        } else {
            return Mage::helper('share')->__('Add Item');
        }
    }
}