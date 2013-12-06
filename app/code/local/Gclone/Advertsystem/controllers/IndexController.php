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
class Gclone_Advertsystem_IndexController extends Mage_Core_Controller_Front_Action {
    const XML_PATH_EMAIL_RECIPIENT = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER = 'contacts/email/sender_email_identity';
    const ADVERT_PATH_EMAIL_TEMPLATE = 'advertsystem/invite/template';

    public function indexAction() {
        $adv = (int) $this->getRequest()->getQuery('adv', 0);
        if ($adv) {
            $resource = Mage::getSingleton('core/resource');
            $read = $resource->getConnection('read');
            $tPrefix = (string) Mage::getConfig()->getTablePrefix();
            $inviteTable = $tPrefix . 'advert_system_invite';
            $select = $read->select()
                            ->from(array('ct' => $inviteTable), array('ct.*'))
                            ->where('ct.invite_id = (?)', $adv);
            $invite = $read->fetchAll($select);
            if (count($invite) && !$invite[0]['friend_id']) {
                Mage::helper('advertsystem/adverts')->setCookie($adv);
            } elseif ($invite[0]['friend_id']) {
                Mage::getSingleton('core/session')->addError('OOPS! Link already used.');
            } else {
                Mage::getSingleton('core/session')->addError('Invalid link.');
            }
        }

        $this->_redirect('customer/account');
    }

    public function inviteSendAction() {
        $session = Mage::getSingleton('customer/session');
        $data = $this->getRequest()->getPost('invite', array());
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('read');
        $tPrefix = (string) Mage::getConfig()->getTablePrefix();
        $customerTable = $tPrefix . 'customer_entity';
        $customerId = $session->getId();
        $selectCustomer = $read->select()
                        ->from(array('ct' => $customerTable), array('ct.email'))
                        ->where('ct.entity_id = (?)', $customerId);
        $fetchCustomer = $read->fetchAll($selectCustomer);
        $customerEmail = $fetchCustomer[0]['email'];
        $friendEmail = $data['email'];
        $friendName = $data['name'];
        $subject = $data['subject'];
        $message = $data['message'];
        $selectFriend = $read->select()
                        ->from(array('ct' => $customerTable), array("count('ct.*') as count"))
                        ->where('ct.email = (?)', $friendEmail);
        $fetchFriend = $read->fetchAll($selectFriend);
        $inviteTable = $tPrefix . 'advert_system_invite';

        $selectInvite = $read->select()
                        ->from(array('ct' => $inviteTable), array("count('ct.*') as count"))
                        ->where('ct.friend_email = (?)', $friendEmail);
        $fetchInvite = $read->fetchAll($selectInvite);

        //Check already a customer or not
        if ($fetchFriend[0]['count'] == 0) {
            try {
                if (!$session->isLoggedIn()) {
                    Mage::getSingleton('core/session')->addError($this->__('Please login to invite your friend.'));
                } else {
                    //Already invited or not
                    if ($fetchInvite[0]['count'] == 0) {
                        $insert = $read->insert($resource->getTableName('advert_system_invite'),
                                 array('invite_id' => '', 'customer_id' => $customerId, 'friend_id' => '', 'friend_name' => $friendName, 'friend_email' => $friendEmail, 'created_at' => '', 'expired' => '0')
                        );
                        $inviteId = $read->lastInsertId();
                        $inviteSend = $this->send($customerId, $friendEmail, $friendName, $customerEmail, $subject, $message, $inviteId);
                        if ($inviteSend) {
                            Mage::getSingleton('core/session')->addSuccess($this->__('Invitation successfully sent.'));
                        } else {
                            $deleteQuery = $read->query("delete from $inviteTable where invite_id = $inviteId");
                            Mage::getSingleton('core/session')->addError('Invitation cannot be sent.Try Again Later');
                        }
                    } else {
                        Mage::getSingleton('core/session')->addError('Your friend already invited.');
                    }
                }
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('core/session')->addError($e->getMessage());
            }
        } else {
            Mage::getSingleton('core/session')->addError('Your friend already a customer.');
        }

        $this->_redirect('advertsystem/index/invitation');
    }

    public function send($customerId, $friendEmail, $friendName, $customerEmail, $subject, $message, $inviteId) {

        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('read');
        $tPrefix = (string) Mage::getConfig()->getTablePrefix();
        $customerTableVarchar = $tPrefix . 'customer_entity_varchar';
        $session = Mage::getSingleton('customer/session');
        $customerId = $session->getId();
        $selectCustomerVarchar = $read->select()
                        ->from(array('ct' => $customerTableVarchar), array('ct.*'))
                        ->where('ct.entity_id =? ', $customerId)
                        ->where('ct.attribute_id =?', 5);
        $customerRecordVarchar = $read->fetchAll($selectCustomerVarchar);
        $customerName = $customerRecordVarchar[0]['value'];

        $postObject = new Varien_Object();
        $storeName = Mage::getStoreConfig("design/head/default_title");
        $invitationLink = Mage::getBaseUrl() . 'advertsystem/?adv=' . $inviteId;
        $postObject->setData(array('friend_name' => $friendName, 'customer_email' => $customerEmail, 'subject' => $subject, 'msg' => $message, 'invitation_link' => $invitationLink, 'customer_name' => $customerName));

        $mailTemplate = Mage::getModel('core/email_template');
        $mailTemplate->setSenderName(Mage::getStoreConfig('design/head/default_title'));
        $mailTemplate->setSenderEmail(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT));
        $mailTemplate->setTemplateSubject($subject);

        /* @var $mailTemplate Mage_Core_Model_Email_Template */
       $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                ->sendTransactional(
                        Mage::getStoreConfig(self::ADVERT_PATH_EMAIL_TEMPLATE),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        $friendEmail,
                        $friendName,
                        array('friend_name' => $friendName, 'customer_email' => $customerEmail, 'subject' => $subject, 'msg' => $message, 'invitation_link' => $invitationLink, 'customer_name' => $customerName)
        );
        if ($mailTemplate->getSentSuccess()) {
            return true;
        } else {
            return false;
        }
    }

    public function invitationAction() {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');

        $this->getLayout()->getBlock('head')->setTitle($this->__('My Advert Invitations'));

        if ($block = $this->getLayout()->getBlock('customer.account.link.back')) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
        $session = Mage::getSingleton('customer/session');
        if ($session->isLoggedIn()) {
             $this->renderLayout();
        } else {
            $version = Mage::getVersion();
            $cookie = Mage::getSingleton('core/cookie');
            if (version_compare($version, '1.2.1', '<')) {
                $cookie->set('advertClicked', base64_encode(1));
            } else {
                $cookie->set('advertClicked', base64_encode(1), true, '/', null, null, true);
            }
            
            $this->_redirect('customer/account/login');
        }
        
    }

    public function importAction() {
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $tPrefix = (string) Mage::getConfig()->getTablePrefix();
        $inviteImport = $tPrefix . 'aw_raf_invite';
        $discountImport = $tPrefix . 'aw_raf_referrer_discount';
        $inviteExport = $tPrefix . 'advert_system_invite';
        $discountExport = $tPrefix . 'advert_system_discount';
        $selectInvite = $read->select()
                        ->from($inviteImport, array('*'));
        $inviteRecords = $read->fetchAll($selectInvite);
        if (count($inviteRecords) > 0) {
            foreach ($inviteRecords as $key => $record) {
                $refererId = $record['referrer_id'];
                $referralId = $record['referral_id'];
                $frndName = $record['referral_name'];
                $frndEmail = $record['referral_email'];
                $createdAt = $record['created_at'];
                $insert = $read->insert($resource->getTableName('advert_system_invite'),
                                 array('invite_id' => '', 'customer_id' => $refererId, 'friend_id' => $referralId, 'friend_name' => $frndName, 'friend_email' => $frndEmail, 'created_at' => $createdAt, 'expired' => '0')
                );

            }
        }
        $selectDiscount = $read->select()
                        ->from($discountImport, array('sum(amount) as amount,referrer_id'))
                        ->where('discount_used =?', 0);
        $discountRecords = $read->fetchAll($selectDiscount);
        if (count($discountRecords) > 0) {
            foreach ($discountRecords as $key => $record) {
                $refererId = $record['referrer_id'];
                $amount = $record['amount'];
                $insert = $read->insert($resource->getTableName('advert_system_discount'),
                                 array('discount_id' => '', 'customer_id' => $refererId, 'amount' => $amount, 'used' => '0', 'remaining' => '0')
                );

            }
        }
    }

    public function dropAction() {
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $tPrefix = (string) Mage::getConfig()->getTablePrefix();
        $inviteTable = $tPrefix . 'aw_raf_invite';
        $discountTable = $tPrefix . 'aw_raf_referrer_discount';
        $turnOverTable = $tPrefix . 'aw_raf_referrer_turnover';
        $historyTable = $tPrefix . 'aw_raf_history';
        $ruleTable = $tPrefix . 'aw_raf_rule';
        $usedLinkTable = $tPrefix . 'aw_raf_usedlink';
        $deleteInvite = $read->query("drop table $inviteTable");
        $deleteDiscount = $read->query("drop table $discountTable");
        $deleteHistory = $read->query("drop table $historyTable");
        $deleteUsedLink = $read->query("drop table $usedLinkTable");
        $deleteRule = $read->query("drop table $ruleTable");
        $deleteTurnOver = $read->query("drop table $turnOverTable");
    }

}