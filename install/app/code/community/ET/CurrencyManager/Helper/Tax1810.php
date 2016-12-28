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
class ET_CurrencyManager_Helper_Tax1810 extends Mage_Tax_Helper_Data
{

    /**
     * Get product price with all tax settings processing
     *
     * @param   Mage_Catalog_Model_Product $product
     * @param   float $price inputed product price
     * @param   bool $includingTax return price include tax flag
     * @param   null|Mage_Customer_Model_Address $shippingAddress
     * @param   null|Mage_Customer_Model_Address $billingAddress
     * @param   null|int $ctc customer tax class
     * @param   null|Mage_Core_Model_Store $store
     * @param   bool $priceIncludesTax flag what price parameter contain tax
     * @return  float
     */
    public function getPrice($product, $price, $includingTax = null, $shippingAddress = null, $billingAddress = null,
        $ctc = null, $store = null, $priceIncludesTax = null, $roundPrice = true
    ) {
        if (!$price) {
            return $price;
        }
        $store = Mage::app()->getStore($store);
        if (!$this->needPriceConversion($store)) {
            return $price;
        }
        Mage::app()->getStore($store)->setDoNotRoundET(true);
        $result = parent::getPrice($product, $price, $includingTax, $shippingAddress, $billingAddress, $ctc,
            $store, $priceIncludesTax, $roundPrice);
        Mage::app()->getStore($store)->setDoNotRoundET(false);
        return $result;
    }
}