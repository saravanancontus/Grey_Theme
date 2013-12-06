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
class Contus_Merchant_Model_Currency {

    public function toOptionArray() {
        return array(
            array('value' => 'USD', 'label' => 'U.S. Dollar'),
            array('value' => 'AUD', 'label' => 'Australian Dollar'),
            array('value' => 'CAD', 'label' => 'Canadian Dollar'),
            array('value' => 'CZK', 'label' => 'Czech Koruna'),
            array('value' => 'DKK', 'label' => 'Danish Krone'),
            array('value' => 'EUR', 'label' => 'Euro'),
            array('value' => 'HKD', 'label' => 'Hong Kong Dollar'),
            array('value' => 'HUF', 'label' => 'Hungarian Forint'),
            array('value' => 'ILS', 'label' => 'Israeli New Sheqel'),
            array('value' => 'JPY', 'label' => 'Japanese Yen'),
            array('value' => 'MXN', 'label' => 'Mexican Peso'),
            array('value' => 'NOK', 'label' => 'Norwegian Krone'),
            array('value' => 'NZD', 'label' => 'New Zealand Dollar'),
            array('value' => 'PHP', 'label' => 'Philippine Peso'),
            array('value' => 'PLN', 'label' => 'Polish Zloty'),
            array('value' => 'GBP', 'label' => 'Pound Sterling'),
            array('value' => 'SGD', 'label' => 'Singapore Dollar'),
            array('value' => 'SEK', 'label' => 'Swedish Krona'),
            array('value' => 'CHF', 'label' => 'Swiss Franc'),
            array('value' => 'TWD', 'label' => 'Taiwan New Dollar'),
            array('value' => 'THB', 'label' => 'Thai Baht'),
            array('value' => 'TRY', 'label' => 'Turkish Lira'),
            array('value' => 'BRL', 'label' => 'Brazilian Real'),
            array('value' => 'MYR', 'label' => 'Malaysian Ringgit'),
        );
    }

}
