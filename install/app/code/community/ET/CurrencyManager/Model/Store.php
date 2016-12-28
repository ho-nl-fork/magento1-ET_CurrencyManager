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

class ET_CurrencyManager_Model_Store extends Mage_Core_Model_Store
{

    /**
     * Round price
     *
     * @param mixed $price
     * @return double
     */
    public function roundPrice($price)
    {
        // fixed double rounding for stores, which use non base display currency and product prices include taxes
        // http://support.etwebsolutions.com/issues/984
        if (Mage::app()->getStore()->getDoNotRoundET()) {
            return $price;
        }


        $options = Mage::helper('currencymanager')->getOptions(array());
        $data = new Varien_Object(array(
            "price" => $price,
            "format" => $options,
        ));

        Mage::dispatchEvent("currency_options_after_get", array("options" => $data));
        $options = $data->getData("format");
        $price = $data->getData("price");

        return round($price, isset($options["precision"]) ? $options["precision"] : 2);
    }
}
