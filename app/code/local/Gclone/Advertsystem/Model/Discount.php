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

class Gclone_AdvertSystem_Model_Discount extends Mage_SalesRule_Model_Quote_Discount {

    protected $_discount;

    public function collect(Mage_Sales_Model_Quote_Address $address) {//Mage_Sales_Model_Quote_Item_Abstract $item,
        parent::collect($address);
        $quote = $address->getQuote();
        $store = Mage::app()->getStore($quote->getStoreId());

        $items = $this->_getAddressItems($address);
        /*if (!count($items)) {
            return $this;
        }*/

        $eventArgs = array(
            'website_id' => $store->getWebsiteId(),
            'customer_group_id' => $quote->getCustomerGroupId(),
            'coupon_code' => $quote->getCouponCode(),
        );

        $this->_calculator->init($store->getWebsiteId(), $quote->getCustomerGroupId(), $quote->getCouponCode());
        $address->setDiscountDescription(array());

        /* foreach ($items as $item) {
            if ($item->getNoDiscount()) {
                $item->setDiscountAmount(0);
                $item->setBaseDiscountAmount(0);
            } else {
                /**
                 * Child item discount we calculate for parent
                 */
                /* if ($item->getParentItemId()) {
                    continue;
                }

                $eventArgs['item'] = $item;
                Mage::dispatchEvent('sales_quote_address_discount_item', $eventArgs);

                if ($item->getHasChildren() && $item->isChildrenCalculated()) {
                    foreach ($item->getChildren() as $child) {
                        $this->_calculator->process($child);
                        $eventArgs['item'] = $child;
                        Mage::dispatchEvent('sales_quote_address_discount_item', $eventArgs);
                        $this->_aggregateItemDiscount($child);
                    }
                } else {
                    $this->_calculator->process($item);
                    $this->_aggregateItemDiscount($item);
                }
            }
        } */

        /**
         * Process shipping amount discount
         */
        $address->setShippingDiscountAmount(0);
        $address->setBaseShippingDiscountAmount(0);
        if ($address->getShippingAmount()) {
            $this->_calculator->processShippingAmount($address);
            $this->_addAmount(-$address->getShippingDiscountAmount());
            $this->_addBaseAmount(-$address->getBaseShippingDiscountAmount());
        }

        $this->_calculator->prepareDescription($address);
        return $this;
    }

   
protected function _aggregateItemDiscount($address)
	{ 

		/*Database connectivity*/
		$session = Mage::getSingleton('customer/session');
		$resource = Mage::getSingleton('core/resource');
		$read = $resource->getConnection('read');
		/*Getting table prefix*/
		$tPrefix = (string) Mage::getConfig()->getTablePrefix();
		
		$discountTable = $tPrefix . 'advert_system_discount';
        $ruleTable = $tPrefix . 'advert_system_rule';
        $customerId = $session->getId();
        $selectRule = $read->select()
                        ->from(array('ct' => $ruleTable), array('ct.*'));
        $fetchRule = $read->fetchAll($selectRule);
        $minAmount = $fetchRule[0]['min_order_amount'];
        $discountAmount = 0;
        $percentAmount = 0;
        $selectCustomer = $read->select()
                        ->from(array('ct' => $discountTable), array('ct.*'))
                        ->where('ct.customer_id = (?)', $customerId)
                        ->where('ct.used = (?)', 0);
                 
        $fetchCustomer = $read->fetchAll($selectCustomer);
        if($fetchCustomer[0]['amount'] != 0){
            $discountAmount = $fetchCustomer[0]['amount'];
        }else{
            $percentAmount = $fetchCustomer[0]['percent'];
        }
		$baseMoney = $discountAmount;
		$lastQid = Mage::getSingleton('checkout/session')->getQuoteId();
		$customerQuote = Mage::getModel('sales/quote')->load($lastQid);
		$subTotal = $customerQuote->getSubtotal();
		$cartItems = Mage::helper('checkout/cart')->getCart()->getItemsCount();
        $money = Mage::helper('core')->currency($baseMoney, false, true);
        if($percentAmount != 0){
                $baseMoney = ($percentAmount / 100) * $subTotal;
                $money = Mage::helper('core')->currency($baseMoney, false, true);
        }


        $enableAdvert = Mage::getStoreConfig('advertsystem/invite/enabled_module');
        if($enableAdvert != 1){
        	$baseMoney = 0;
        	$money = 0;
        }
		
		if ((($subTotal >= $money || $percentAmount != 0)) && $subTotal >= $minAmount) {

			$address->setDiscountAmount($address->getDiscountAmount()+($money/$cartItems));
			$address->setBaseDiscountAmount($address->getBaseDiscountAmount()+($baseMoney/$cartItems));
				
		}
       

		$this->_addAmount(-($address->getDiscountAmount()));
		$this->_addBaseAmount(-($address->getBaseDiscountAmount()));

		return $this;
	}

}