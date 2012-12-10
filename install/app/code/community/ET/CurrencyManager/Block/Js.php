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

class ET_CurrencyManager_Block_Js  extends Mage_Core_Block_Template
{
    public function getJsonConfig()
    {
        if (method_exists(Mage::helper('core'), 'jsonEncode')) {
            return Mage::helper('core')->jsonEncode(
                Mage::helper('currencymanager')->getOptions(
                    array(),
                    false,
                    Mage::app()->getStore()->getCurrentCurrencyCode()
                )
            );
        } else {
            return Zend_Json::encode(
                Mage::helper('currencymanager')->getOptions(
                    array(),
                    false,
                    Mage::app()->getStore()->getCurrentCurrencyCode()
                )
            );
        }
    }
}