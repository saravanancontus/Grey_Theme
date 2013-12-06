<?php
/**
 * Author   : Contus Support
 *
 * * NOTICE OF LICENSE
 *
 * This source file is subject to the CONTUS ADVERT SYSTEM (REFER A FRIEND)
 * License, which extends the  GNU General Public License (GPL).
 * The CONTUS ADVERT SYSTEM (REFER A FRIEND) License is available at this URL:
 *      http://license.txt
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
$installer = $this;

$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('advert_system_discount')};
CREATE TABLE  {$this->getTable('advert_system_discount')} (
  `discount_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `order_id` int(10) NOT NULL,
  `previous_amount` decimal(12,4) NOT NULL,
  `amount` decimal(12,4) unsigned NOT NULL,
  `percent` int(11) NOT NULL,
  `used` int(10) unsigned NOT NULL DEFAULT '0',
  `remaining` int(11) NOT NULL,
  `discount_type_priority` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`discount_id`),
  KEY `referrer_id` (`customer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- DROP TABLE IF EXISTS {$this->getTable('advert_system_invite')};
CREATE TABLE IF NOT EXISTS {$this->getTable('advert_system_invite')} (
  `invite_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `friend_id` int(10) unsigned NOT NULL DEFAULT '0',
  `friend_name` varchar(255) NOT NULL,
  `friend_email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expired` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`invite_id`),
  KEY `referrer_id` (`customer_id`),
  KEY `referral_id` (`friend_id`),
  KEY `count` (`customer_id`,`friend_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;



-- DROP TABLE IF EXISTS {$this->getTable('advert_system_rule')};
CREATE TABLE {$this->getTable('advert_system_rule')} (
  `rule_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rule_type` tinyint(1) unsigned NOT NULL,
  `referral_limit` int(10) unsigned NOT NULL,
  `purchase_type` int(11) NOT NULL,
  `discount_type` int(11) NOT NULL,
  `discount_amount` decimal(12,4) unsigned NOT NULL,
  `percent_amount` int(11) NOT NULL,
  `min_order_amount` decimal(12,4) unsigned NOT NULL DEFAULT '0.0000',
  `max_invite_limit` int(11) NOT NULL,
  `max_percent_limit` int(11) NOT NULL,
  PRIMARY KEY (`rule_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;



INSERT INTO  {$this->getTable('advert_system_rule')} (`rule_id`, `rule_type`, `referral_limit`, `purchase_type`, `discount_type`, `discount_amount`, `percent_amount`, `min_order_amount`, `max_invite_limit`, `max_percent_limit`) VALUES
(1, 1, 1, 1, 2, 15.0000, 2, 20.0000, 5, 40);
    ");
$installer->endSetup();
