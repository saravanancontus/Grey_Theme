<?php
/**
 * Author   : Contus Support
 *
 * * NOTICE OF LICENSE
 *
 * This source file is subject to the CONTUS ADVERT SYSTEM (REFER A FRIEND)
 * License, which extends the  GNU General Public License (GPL).
 * The CONTUS ADVERT SYSTEM (REFER A FRIEND) License is available at this URL:
 *     http://www.contussupport.com/magento/CONTUS-ADVERT-SYSTEM-LICENSE-COMMUNITY.txt
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
class Gclone_Advertsystem_Block_Adminhtml_Adcustomer_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('adcustomerGrid');
        $this->setDefaultSort('entity_id');
        $this->setVarNameDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getResourceModel('advertsystem/customer_collection')->addNameToSelect();
       
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('invite_id', array(
            'header' => Mage::helper('advertsystem')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'filter' => false,
            'sortable' => false,
            'index' => 'invite_id',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('advertsystem')->__('Customer Name'),
            'align' => 'left',
            'filter' => false,
            'sortable' => false,
            'index' => 'name',
        ));

        $this->addColumn('email', array(
            'header' => Mage::helper('advertsystem')->__('Customer Email'),
            'align' => 'left',
            'filter' => false,
            'sortable' => false,
            'index' => 'email',
        ));
        
        $this->addColumn('amount', array(
            'header' => Mage::helper('advertsystem')->__('Discount Amount'),
            'align' => 'left',
            'sortable' => false,
            'filter' => false,
            'index' => 'amount',
        ));
        

        return parent::_prepareColumns();
    }
    
}