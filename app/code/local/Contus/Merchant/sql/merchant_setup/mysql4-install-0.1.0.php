<?php
/**
 * Contus Support Interactive.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file GCLONE-LICENSE.txt.
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento 1.4.1.1 COMMUNITY edition
 * Contus Support does not guarantee correct work of this package
 * on any other Magento edition except Magento 1.4.1.1 COMMUNITY edition.
 * =================================================================
 */
$installer = $this;

$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('deal_payment')};
CREATE TABLE {$this->getTable('deal_payment')} (
  `sno` int(11) NOT NULL AUTO_INCREMENT,
  `payer_id` varchar(25) NOT NULL,
  `payer_email` varchar(50) NOT NULL,
  `product_id` int(15) NOT NULL,
  `amount` varchar(10) NOT NULL,
  `date` varchar(25) NOT NULL,
  `payment_status` varchar(15) NOT NULL,
  `admin_approved` varchar(5) NOT NULL,
  PRIMARY KEY (`sno`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

ALTER TABLE {$this->getTable('sales_flat_order')} ADD `merchant_id` INT NOT NULL AFTER `customer_id`;
ALTER TABLE {$this->getTable('sales_flat_order_grid')} ADD `merchant_id` INT NOT NULL AFTER `customer_id`;
    ");

$installer->endSetup(); 