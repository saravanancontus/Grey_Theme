<?php
/**
 * Contus Support Interactive.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file GCLONE-LICENSE.txt.
 * The CONTUS GCLONE License is available at this URL:
 * http://www.groupclone.net/GCLONE-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Contus Support does not guarantee correct work of this package
 * on any other Magento edition except Magento COMMUNITY edition.
 * =================================================================
 */

class Gclone_All_Block_Adminhtml_All_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'all';
        $this->_controller = 'adminhtml_all';
        
        $this->_updateButton('save', 'label', Mage::helper('all')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('all')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('all_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'all_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'all_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('all_data') && Mage::registry('all_data')->getId() ) {
            return Mage::helper('all')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('all_data')->getTitle()));
        } else {
            return Mage::helper('all')->__('Add Item');
        }
    }
}