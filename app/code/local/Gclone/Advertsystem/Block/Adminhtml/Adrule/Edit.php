<?php
/**
 * Author   : Contus Support
 *
 * * NOTICE OF LICENSE
 *
 * This source file is subject to the CONTUS ADVERT SYSTEM (REFER A FRIEND)
 * License, which extends the  GNU General Public License (GPL).
 * The CONTUS ADVERT SYSTEM (REFER A FRIEND) License is available at this URL:
 *      http://www.contussupport.com/magento/CONTUS-ADVERT-SYSTEM-LICENSE-COMMUNITY.txt
 *
 * DISCLAIMER
 *
 * By adding to, editing, or in any way modifying this code, CONTUS is
 * not held liable for any inconsistencies or abnormalities in the
 * behaviour of this code.
 * By adding to, editing, or in any way modifying this code, the Licensee
 * terminates any agreement of support offered by CONTUS, outlined in the
 * provided Sweet Tooth License.
 * Upon discovery of modified code in the process of support, the Licensee
 * is still held accountable for any and all billable time CONTUS spent
 * during the support process.
 * CONTUS does not guarantee compatibility with any other framework extension.
 * CONTUS is not responsbile for any inconsistencies or abnormalities in the
 * behaviour of this code if caused by other framework extension.
 * Also distributing the code is prohibited ,It is accountable if violated license agreement.
 */
class Gclone_Advertsystem_Block_Adminhtml_Adrule_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'advertsystem';
        $this->_controller = 'adminhtml_adrule';

        $this->_updateButton('save', 'label', Mage::helper('advertsystem')->__('Save Item'));

        $this->_removeButton('delete');

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('advertsystem_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'advertsystem_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'advertsystem_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText() {
        if (Mage::registry('advertsystem_data') && Mage::registry('advertsystem_data')->getId()) {
            return Mage::helper('advertsystem')->__("Edit Rule");
        } else {
            return Mage::helper('advertsystem')->__('Add Item');
        }
    }

}