<?php
$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('ordercustomer')};
CREATE TABLE {$this->getTable('ordercustomer')} (
  `ordercustomer_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(5) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `uniqueid` varchar(200) NOT NULL,
  `product_name` text NOT NULL,
  `created_time` datetime DEFAULT NULL,
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `quantity` int(3) DEFAULT NULL,
  `quantitynumber` int(10) NOT NULL,
  `couponstatus` int(11) NOT NULL,
  PRIMARY KEY (`ordercustomer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

    ");

$installer->endSetup();