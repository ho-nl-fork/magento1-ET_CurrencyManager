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

class ET_CurrencyManager_Model_Observer
{

    public function fixCurrencySwitchUrl(Varien_Event_Observer $observer)
    {
        $isFixEnabled = (int)Mage::getConfig()->getNode('default/currencymanager/additional/fix_currency_switch_url');
        if ($isFixEnabled) {
            //helper rewrite
            Mage::getConfig()->setNode('global/helpers/directory/rewrite/url', 'ET_CurrencyManager_Helper_Url');

            //controller rewrite
            Mage::getConfig()->setNode('global/rewrite/currencymanager_switch_currency/from',
                '#^/directory/currency#');
            Mage::getConfig()->setNode('global/rewrite/currencymanager_switch_currency/to',
                '/currencymanager/currency');
        }
    }
}