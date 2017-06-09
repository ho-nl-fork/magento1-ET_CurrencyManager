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

class ET_CurrencyManager_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * ZEND constants avalable in /lib/Zend/Currency.php
     *
     * NOTICE
     *
     * Magento ver 1.3.x - display - USE_SHORTNAME(3) by default
     * Magento ver 1.4.x - display - USE_SYMBOL(2) by default
     *
     * position: 8 - standart; 16 - right; 32 - left
     *
     */

    protected $_options = array();
    protected $_optionsadvanced = array();

    public function getOptions($options = array(), $old = false, $currency = "default") //$old for support Magento 1.3.x
    {
        $storeId = Mage::app()->getStore()->getStoreId();
        if ((!isset($this->_options[$storeId][$currency])) || (!isset($this->_optionsadvanced[$storeId][$currency]))) {
            $this->setOptions($currency);
        }
        
        $newOptions = $this->_options[$storeId][$currency];
        $newOptionsAdvanced = $this->_optionsadvanced[$storeId][$currency];

        if (!$old) {
            $newOptions = $newOptions + $newOptionsAdvanced;
        }

        // For JavaScript prices: Strange Symbol extracting in function getOutputFormat
        // in file app/code/core/Mage/Directory/Model/Currency.php
        // For Configurable, Bundle and Simple with custom options
        // This causes problem if any currency has by default NO_SYMBOL
        // with this module can't change display value in this case
        if (isset($options["display"])) {
            if ($options["display"] == Zend_Currency::NO_SYMBOL) {
                unset($newOptions["display"]);
            }
        }

        if (is_array($options) && !empty($options)) {
            $newOptions = $newOptions + $options;
        }

        return $newOptions;
    }

    public function clearOptions($options)
    {
        $oldOptions = array("position", "format", "display", "precision", "script", "name", "currency", "symbol");
        foreach (array_keys($options) as $optionKey) {
            if (!in_array($optionKey, $oldOptions)) {
                unset($options[$optionKey]);
            }
        }

        return $options;
    }


    public function isEnabled()
    {
        $config = Mage::getStoreConfig('currencymanager/general');
        $storeId = Mage::app()->getStore()->getStoreId();
        return ((($config['enabled']) && ($storeId > 0)) || (($config['enabledadm']) && ($storeId == 0)));
    }


    public function setOptions($currency = "default")
    {
        $config = Mage::getStoreConfig('currencymanager/general');

        $options = array();
        $optionsAdvanced = array();
        $storeId = Mage::app()->getStore()->getStoreId();
        if ($this->isEnabled()) {
            $notCheckout = !($config['excludecheckout'] & $this->isInOrder());
            $this->_getGeneralOptions($config, $options, $optionsAdvanced, $notCheckout);

            // formatting symbols from admin, preparing to use. Maybe can do better :)
            // если в админке будут внесены
            // несколько значений для одной валюты,
            // то использоваться будет только одна
            if (isset($config['symbolreplace'])) {
                $this->_collectCurrencyOptions($config, $currency, $notCheckout, $options, $optionsAdvanced);
            }
        } // end NOT ENABLED

        $this->_options[$storeId][$currency] = $options;
        $this->_optionsadvanced[$storeId][$currency] = $optionsAdvanced;
        if (!isset($this->_options[$storeId]["default"])) {
            $this->_options[$storeId]["default"] = $options;
            $this->_optionsadvanced[$storeId]["default"] = $optionsAdvanced;
        }

        return $this;
    }

    protected function _getGeneralOptions($config, &$options, &$optionsAdvanced, $notCheckout)
    {
        if ($notCheckout) {
            if (isset($config['precision'])) { // precision must be in range -1 .. 30
                $options['precision'] = min(30, max(-1, (int)$config['precision']));
            }

            if (isset($config['zerotext'])) {
                $optionsAdvanced['zerotext'] = $config['zerotext'];
            }
        }

        if (isset($config['position'])) {
            $options['position'] = (int)$config['position'];
        }

        if (isset($config['display'])) {
            $options['display'] = (int)$config['display'];
        }


        if (isset($config['input_admin'])) {
            if ($config['input_admin'] > 0) {
                $optionsAdvanced['input_admin'] = (int)$config['input_admin'];
            }
        }

        $optionsAdvanced['excludecheckout'] = $config['excludecheckout'];
        $optionsAdvanced['cutzerodecimal'] = $config['cutzerodecimal'];
        $optionsAdvanced['cutzerodecimal_suffix'] = isset($config['cutzerodecimal_suffix']) ?
            $config['cutzerodecimal_suffix'] : "";
        $optionsAdvanced['min_decimal_count'] = isset($config['min_decimal_count']) ?
            $config['min_decimal_count'] : "2";
    }

    protected function _collectCurrencyOptions($config, $currency, $notCheckout, &$options, &$optionsAdvanced)
    {
        $symbolReplace = $this->_unsetSymbolReplace($config);

        if (!empty($symbolReplace['currency'])) {
            $tmpOptions = array();
            $tmpOptionsAdvanced = array();

            $tmpOptionsAdvanced['cutzerodecimal'] = $this->_getCurrencyOption(
                $currency, $symbolReplace, 'cutzerodecimal', true
            );

            if (isset($symbolReplace['cutzerodecimal_suffix'])) {
                $tmpOptionsAdvanced["cutzerodecimal_suffix"] = $this->_getCurrencyOption(
                    $currency, $symbolReplace, 'cutzerodecimal_suffix'
                );
            }

            if (isset($symbolReplace['min_decimal_count'])) {
                $tmpOptionsAdvanced["min_decimal_count"] = $this->_getCurrencyOption(
                    $currency, $symbolReplace, 'min_decimal_count'
                );
            }


            $tmpOptions['position'] = $this->_getCurrencyOption($currency, $symbolReplace, 'position', true);
            $tmpOptions['display'] = $this->_getCurrencyOption($currency, $symbolReplace, 'display', true);
            $tmpOptions['symbol'] = $this->_getCurrencyOption($currency, $symbolReplace, 'symbol');

            if ($notCheckout) {
                $tmpOptionsAdvanced['zerotext'] = $this->_getCurrencyOption($currency, $symbolReplace, 'zerotext');

                $precision = $this->_getCurrencyOption($currency, $symbolReplace, 'precision', true);
                if ($precision !== false) {
                    $tmpOptions['precision'] = min(30, max(-1, $precision));
                }
            }

            foreach ($tmpOptions as $option => $value) {
                if ($value !== false) {
                    $options[$option] = $value;
                }
            }

            foreach ($tmpOptionsAdvanced as $option => $value) {
                if ($value !== false) {
                    $optionsAdvanced[$option] = $value;
                }
            }
        }
    }

    /**
     * To check where price is used
     * in some cases default values for precision and zerotext should be used
     * for sales/checkout in frontend
     * for admin AND sales_order*
     *
     * @return bool
     */
    public function isInOrder()
    {
        $moduleName = Mage::app()->getRequest()->getModuleName();
        $controllerName = Mage::app()->getRequest()->getControllerName();

        $orderModules = array('sales', 'checkout', 'paypal');
        $controllerNameList = array('sales_invoice', 'sales_order',
            'sales_shipment', 'sales_creditmemo');
        $modifiedOrderModules = array(
            'order_modules' => new Varien_Object(array('module_names'=>$orderModules)),
        );

        Mage::dispatchEvent('et_currencymanager_checking_is_in_order_before', $modifiedOrderModules);

        $orderModules = $modifiedOrderModules['order_modules']->getData('module_names');
        $foundController = false;
        foreach ($controllerNameList as $controller) {
            if (strpos($controllerName, $controller) !== false) {
                $foundController = true;
                break;
            }
        }

        $adminNode = Mage::getConfig()->getNode(Mage_Adminhtml_Helper_Data::XML_PATH_ADMINHTML_ROUTER_FRONTNAME);
        return ((in_array($moduleName, $orderModules))
            || (($moduleName == (string)($adminNode)) && $foundController));
    }



    /**
     * To check where price is used
     * We need to drop html tags in some places. Example: PDF printing in admin
     *
     * @return bool
     */
    public function isNeedDropTags()
    {
        $action = Mage::app()->getRequest()->getActionName();
        $moduleName = Mage::app()->getRequest()->getModuleName();
        $controllerName = Mage::app()->getRequest()->getControllerName();

        $actionList = array('print');
        $controllerNameList = array('sales_order_invoice',
            'sales_order_shipment', 'sales_order_creditmemo');

        if (in_array($action, $actionList)
            && in_array($controllerName, $controllerNameList)
            && ($moduleName == 'admin')) {
            return true;
        }

        return false;
    }

    protected function _unsetSymbolReplace($config)
    {
        if (!is_array($config['symbolreplace'])) {
            $symbolReplace = unserialize($config['symbolreplace']);
            foreach (array_keys($symbolReplace['currency']) as $symbolReplaceKey) {
                if (trim($symbolReplace['currency'][$symbolReplaceKey]) === "") {
                    unset($symbolReplace['currency'][$symbolReplaceKey]);
                    unset($symbolReplace['precision'][$symbolReplaceKey]);
                    unset($symbolReplace['min_decimal_count'][$symbolReplaceKey]);
                    unset($symbolReplace['cutzerodecimal'][$symbolReplaceKey]);
                    unset($symbolReplace['cutzerodecimal_suffix'][$symbolReplaceKey]);
                    unset($symbolReplace['position'][$symbolReplaceKey]);
                    unset($symbolReplace['display'][$symbolReplaceKey]);
                    unset($symbolReplace['symbol'][$symbolReplaceKey]);
                    unset($symbolReplace['zerotext'][$symbolReplaceKey]);
                }
            }

            return $symbolReplace;
        }

        return false;
    }

    public function resetOptions()
    {
        $this->_options = array();
        $this->_optionsadvanced = array();
    }

    protected function _getCurrencyOption($currency, $symbolReplace, $option, $int = false)
    {
        $configSubData = array_combine($symbolReplace['currency'], $symbolReplace[$option]);
        if (array_key_exists($currency, $configSubData)) {
            $value = $configSubData[$currency];
            if ($value === "") {
                return false;
            }

            return ($int) ? (int)$value : $value;
        }

        return false;
    }

}