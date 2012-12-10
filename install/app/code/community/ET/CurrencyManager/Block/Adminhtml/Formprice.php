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

class ET_CurrencyManager_Block_Adminhtml_Formprice  extends Mage_Adminhtml_Block_Catalog_Product_Helper_Form_Price
{
    public function getEscapedValue($index=null)
    {
        $options = Mage::helper('currencymanager')->getOptions(array());
        $value = $this->getValue();

        if (!is_numeric($value)) {
            return null;
        }

        if (isset($options["input_admin"]) && isset($options['precision'])) {
            return number_format($value, $options['precision'], null, '');
        }

        return parent::getEscapedValue($index);
    }
}