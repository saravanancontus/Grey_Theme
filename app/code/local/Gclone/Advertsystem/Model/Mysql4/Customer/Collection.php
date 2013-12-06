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
class Gclone_Advertsystem_Model_Mysql4_Customer_Collection extends Mage_Customer_Model_Entity_Customer_Collection {

    //first load
    protected function _initSelect() {

        parent::_initSelect();

          $select = $this->getSelect();

     $select->join(
                        array('adcus' => $this->getTable('advertsystem/adcustomer')),
                        "e.entity_id = adcus.customer_id"
                )->joinleft(
                        array('addisc' => $this->getTable('advertsystem/addiscount')),
                        "e.entity_id = addisc.customer_id"
                )
                ->group('e.entity_id');
    }

}