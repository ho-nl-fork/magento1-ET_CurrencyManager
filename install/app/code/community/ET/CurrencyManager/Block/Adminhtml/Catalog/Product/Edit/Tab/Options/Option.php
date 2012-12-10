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
class ET_CurrencyManager_Block_Adminhtml_Catalog_Product_Edit_Tab_Options_Option
    extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Options_Option
{
    /**
     * Rewrite to use custom precision (http://support.etwebsolutions.com/issues/466)
     *
     * @param $value
     * @param $type
     * @return string
     */
    public function getPriceValue($value, $type)
    {
        $options = Mage::helper('currencymanager')->getOptions(array());

        if (isset($options["input_admin"]) && isset($options['precision'])) {
            if ($type == 'percent') {
                return number_format($value, $options['precision'], null, '');
            } elseif ($type == 'fixed') {
                return number_format($value, $options['precision'], null, '');
            }
        }

        return parent::getPriceValue($value, $type);
    }

}