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

    public function rewriteClasses(Varien_Event_Observer $observer)
    {
        $isRewriteEnabled = (int)Mage::getConfig()->getNode('default/currencymanager/additional/rewrite_classes');
        if ($isRewriteEnabled) {
            /** in CE version 1.8.1.0 tax functions declarations changed */
            if (version_compare(Mage::getVersion(), '1.8.1', '>')) {
                //Helper rewrite
                Mage::getConfig()->setNode('global/helpers/tax/rewrite/data', 'ET_CurrencyManager_Helper_Tax1810');
            } else {
                //Helper rewrite
                Mage::getConfig()->setNode('global/helpers/tax/rewrite/data', 'ET_CurrencyManager_Helper_Tax');
            }
        }
    }

    /**
     * Remove html tags from currency symbol for PDF
     *
     * Event: currency_options_after_get
     *
     * @param Varien_Event_Observer $observer
     */
    public function removeHtmlTags(Varien_Event_Observer $observer)
    {
        $options = $observer->getData('options');
        if ($options instanceof Varien_Object) {

            /** @var ET_CurrencyManager_Helper_Data $helper */
            $helper = Mage::helper('currencymanager');

            if ($helper->isNeedDropTags()) {
                $data = $options->getData();
                $data['format']['symbol'] = $helper->removeTags($data['format']['symbol']);
                $options->setData($data);
            }
        }
    }
}
