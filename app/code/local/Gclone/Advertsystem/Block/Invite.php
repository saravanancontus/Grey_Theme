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
class Gclone_Advertsystem_Block_Invite extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('advertsystem/invite.phtml');
    }


    public function getFormUrl()
    {
		return $this->getUrl('advertsystem/index/invitesend/', array('_secure' => Mage::app()->getStore(true)->isCurrentlySecure()));
    }

    public function isLoggedIn()
    {
        return Mage::getSingleton('customer/session')->isLoggedIn();
    }

    public function getFriendName()
    {
        return $this->getData('name');
    }

    public function getSubject()
    {
        if ($this->getData('subject')){
            return $this->getData('subject');
        }
        return $this->__('Referral for %s', Mage::app()->getStore()->getName());
    }

    public function getMessage()
    {
        if ($this->getData('message')){
            return $this->getData('message');
        }
        return $this->__('Hi, I thought it might interest you.');
    }
}
