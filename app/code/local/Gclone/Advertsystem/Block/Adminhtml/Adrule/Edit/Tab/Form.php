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
class Gclone_Advertsystem_Block_Adminhtml_Adrule_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('target_group', array('legend' => Mage::helper('advertsystem')->__('Rule Settings')));

        $fieldset->addField('rule_type', 'select', array(
            'label' => Mage::helper('advertsystem')->__('Rule Type'),
            'name' => 'rule_type',
            'id' => 'rule_type',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('advertsystem')->__('Sign Up'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('advertsystem')->__('Purchased'),
                ),
            ),
            'onchange' => 'changeType()',
        ));



        $fieldset->addField('referral_limit', 'text', array(
            'label' => Mage::helper('advertsystem')->__('Target Limit'),
            'class' => 'validate-greater-than-zero',
            'required' => true,
            'name' => 'referral_limit',
        ));

        $fieldset->addField('max_invite_limit', 'text', array(
            'label' => Mage::helper('advertsystem')->__('Trigger Limit'),
            'class' => 'validate-greater-than-zero',
            'required' => true,
            'name' => 'max_invite_limit',
        ));

        $fieldset->addField('purchase_type', 'select', array(
            'label' => Mage::helper('advertsystem')->__('Purchase Type'),
            'name' => 'purchase_type',
            'id' => 'purchase_type',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('advertsystem')->__('Order'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('advertsystem')->__('Quantity'),
                ),
            ),
        ));

        $fieldset->addField('discount_type', 'select', array(
            'label' => Mage::helper('advertsystem')->__('Discount Type'),
            'name' => 'discount_type',
            'id' => 'discount_type',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('advertsystem')->__('Flat Rate'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('advertsystem')->__('% Percent'),
                ),
            ),
            'onchange' => 'changeDiscountType()',
        ));

        $fieldset->addField('percent_amount', 'text', array(
            'label' => Mage::helper('advertsystem')->__('Percentage'),
            'class' => 'validate-greater-than-zero',
            'required' => false,
            'name' => 'percent_amount',
        ));
        $fieldset->addField('max_percent_limit', 'text', array(
            'label' => Mage::helper('advertsystem')->__('Maximum Percentage Earn Limit'),
            'class' => 'validate-greater-than-zero',
            'required' => false,
            'name' => 'max_percent_limit',
        ));

        $fieldset->addField('discount_amount', 'text', array(
            'label' => Mage::helper('advertsystem')->__('Discount Amount'),
            'class' => 'validate-greater-than-zero',
            'required' => false,
            'name' => 'discount_amount',
        ));

        $fieldset->addField('min_order_amount', 'text', array(
            'label' => Mage::helper('advertsystem')->__('Minimum Order Amount'),
            'class' => 'validate-greater-than-zero',
            'name' => 'min_order_amount',
            'id' => 'min_order_amount',
        ));
        $eventElem = $fieldset->addField('change', 'hidden', array(
                    'name' => 'hidden',
                    'id' => 'hidden',
                ));
        $eventElem->setAfterElementHtml("<script>

		var type = document.getElementById('rule_type').value;
                var discountType = document.getElementById('discount_type').value;

                if(discountType == 1){
                    document.getElementById('percent_amount').disabled=true;
                } else{
                      document.getElementById('percent_amount').disabled=false;
                }
                
                if(type == '1' ){
                    document.getElementById('purchase_type').disabled=true;
                }else{
                     document.getElementById('purchase_type').disabled=false;
                }	


		</script>");

        if (Mage::getSingleton('adminhtml/session')->getAdvertsystemData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getAdvertsystemData());
            Mage::getSingleton('adminhtml/session')->setAdvertsystemData(null);
        } elseif (Mage::registry('advertsystem_data')) {
            $form->setValues(Mage::registry('advertsystem_data')->getData());
        }
        return parent::_prepareForm();
    }

}