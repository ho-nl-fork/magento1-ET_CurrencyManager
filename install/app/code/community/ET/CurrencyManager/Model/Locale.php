<?php
/**
 * NOTICE OF LICENSE
 *
 * You may not sell, sub-license, rent or lease
 * any portion of the Software or Documentation to anyone.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @category   ET
 * @package    ET_CurrencyManager
 * @copyright  Copyright (c) 2012 ET Web Solutions (http://etwebsolutions.com)
 * @contacts   support@etwebsolutions.com
 * @license    http://shop.etwebsolutions.com/etws-license-free-v1/   ETWS Free License (EFL1)
 */

class ET_CurrencyManager_Model_Locale extends Mage_Core_Model_Locale
{
    public function currency($ccode)
    {
        $admcurrency = parent::currency($ccode);
        $options = Mage::helper('currencymanager')->getOptions(array(), true, $ccode);
        $admcurrency->setFormat($options, $ccode);

        return $admcurrency;
    }


    public function getJsPriceFormat()
    {
        // For JavaScript prices
        $parentFormat = parent::getJsPriceFormat();
        $options = Mage::helper('currencymanager')->getOptions(array());
        if (isset($options["precision"])) {
            $parentFormat["requiredPrecision"] = $options["precision"];
            $parentFormat["precision"] = $options["precision"];
        }
        $configAdditional = Mage::getStoreConfig('currencymanager/additional');

        if (isset($configAdditional["change_decimal_group_symbol"])) {
            if ($configAdditional["change_decimal_group_symbol"] == 1) {
                $parentFormat["groupSymbol"] = isset($configAdditional["decimal_group_symbol"])
                    ? $configAdditional["decimal_group_symbol"]
                    : "";
            }
        }

        if (isset($configAdditional["decimal_group_length"])) {
            if ($configAdditional["decimal_group_length"] == 1) {
                $parentFormat["groupLength"] = 3;
            }
        }

        return $parentFormat;
    }
}