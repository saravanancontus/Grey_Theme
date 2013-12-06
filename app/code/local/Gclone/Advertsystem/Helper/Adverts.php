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
class Gclone_Advertsystem_Helper_Adverts extends Mage_Core_Helper_Abstract {

    public function inviteAvailable() {
        $adv = $this->_getRequest()->getCookie('advertsystem', null);
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('read');
        $tPrefix = (string) Mage::getConfig()->getTablePrefix();
        $inviteTable = $tPrefix . 'advert_system_invite';
        if ($adv) {
            $adv = (int) base64_decode(rawurldecode($adv));
            $select = $read->select()
                            ->from(array('ct' => $inviteTable), array('ct.*'))
                            ->where('ct.invite_id = (?)', $adv);
            $invite = $read->fetchAll($select);
            if (($customerId = $invite[0]['customer_id']) && !$invite[0]['friend_id']) {
                return $invite[0]['invite_id'];
            }
        } else {
            return false;
        }
    }

    public function setCookie($inviteId = null) {
        $version = Mage::getVersion();
        $cookie = Mage::getSingleton('core/cookie');
        if (version_compare($version, '1.2.1', '<')) {
            $cookie->set('advertsystem', base64_encode($inviteId));
        } else {
            $cookie->set('advertsystem', base64_encode($inviteId), true, '/', null, null, true);
        }
    }

    public function deleteCookie() {
        $cookie = Mage::getSingleton('core/cookie');
        $cookie->delete('advertsystem', '/', null, null, true);
    }

    public function getReferrerDiscount($referrerId, $convert = true, $string = false, $filterUsed = true) {
        if ($referrerId) {
            $discounts = Mage::getResourceModel('advertsystem/discount_collection')->loadByReferrer($referrerId);
            $_discounts = array();
            if (count($discounts)) {
                if ($convert) {
                    $discounts->walk('afterLoadConvert');
                }
                if ($filterUsed) {
                    foreach ($discounts->getItems() as $key => $discount) {
                        $rule = Mage::getSingleton('advertsystem/rule')->load($discount->getRuleId());
                        if ($rule->getDiscountUsage() != 0 && $rule->getDiscountUsage() <= $discount->getDiscountUsed()) {
                            $discounts->removeItemByKey($key);
                        }
                    }
                }

                if ($string) {
                    $discountsByType = array();
                    $removeIds = array();
                    foreach ($discounts->getItems() as $discount) {
                        if (isset($discountsByType[$discount->getType()])) {
                            $discountsByType[$discount->getType()] += $discount->getAmount();
                            $removeIds[] = $discount->getId();
                        } else {
                            $discountsByType[$discount->getType()] = $discount->getAmount();
                        }
                    }
                    if (count($removeIds)) {
                        foreach ($removeIds as $key) {
                            $discounts->removeItemByKey($key);
                        }
                    }
                    foreach ($discounts->getItems() as $discount) {
                        if (isset($discountsByType[$discount->getType()])) {
                            $discount->setAmount($discountsByType[$discount->getType()]);
                        }
                    }
                    $discounts->walk('afterLoadFormat');
                    foreach ($discounts->getItems() as $discount) {
                        $_discounts[] = $discount->getAmount();
                    }
                    $discount = implode(' + ', $_discounts);

                    if (!count($_discounts)) {
                        return 'none';
                    }
                    return $discount;
                }
                return $discounts;
            } else {
                if ($string) {
                    return $this->__('none');
                }
            }
        }
        return null;
    }

    public function availableDiscount() {
        
    }

    public function getCouponCode() {
        return $this->getDiscount(true, true);
    }

    public function getCouponCodeDescription($code) {
        return $this->__('Your %s discount for advert friends', $code);
    }

}
