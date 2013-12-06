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
class Gclone_Advertsystem_Block_Adminhtml_Adrule_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('advertsystemGrid');
        $this->setDefaultSort('rule_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('advertsystem/adrule')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

       
        $this->addColumn('rule_type', array(
            'header' => Mage::helper('advertsystem')->__('Rule Type'),
            'align' => 'left',
            'index' => 'rule_type',
            'filter' => false,
            'sortable' => false,
             'type'      => 'options',
          'options'   => array(
              1 => 'Sign Up',
              2 => 'Purchased',
          ),
        ));

        $this->addColumn('referral_limit', array(
            'header' => Mage::helper('advertsystem')->__('Target Limit'),
            'align' => 'left',
            'index' => 'referral_limit',
            'filter' => false,
            'sortable' => false,
        ));

        $this->addColumn('max_invite_limit', array(
            'header' => Mage::helper('advertsystem')->__('Trigger Limit'),
            'align' => 'left',
            'index' => 'max_invite_limit',
            'filter' => false,
            'sortable' => false,
        ));

          $this->addColumn('discount_type', array(
            'header' => Mage::helper('advertsystem')->__('Discount Percentage'),
            'align' => 'left',
            'index' => 'discount_type',
            'filter' => false,
            'sortable' => false,
        ));

        $this->addColumn('discount_amount', array(
            'header' => Mage::helper('advertsystem')->__('Discount Amount'),
            'align' => 'left',
            'index' => 'discount_amount',
            'filter' => false,
            'sortable' => false,
        ));


        $this->addColumn('min_order_amount', array(
            'header' => Mage::helper('advertsystem')->__('Min Order Amount'),
            'align' => 'left',
            'index' => 'min_order_amount',
            'filter' => false,
            'sortable' => false,
        ));


        $this->addColumn('admin_action',
                array(
                    'header' => Mage::helper('advertsystem')->__('Action'),
                    'width' => '100',
                    'type' => 'action',
                    'getter' => 'getId',
                    'actions' => array(
                        array(
                            'caption' => Mage::helper('advertsystem')->__('Edit'),
                            'url' => array('base' => '*/*/edit'),
                            'field' => 'id'
                        )
                    ),
                    'filter' => false,
                    'sortable' => false,
                    'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}