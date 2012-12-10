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

class ET_CurrencyManager_Block_Adminhtml_Support
    extends Mage_Adminhtml_Block_Abstract
        implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * Support tab
     * version 1.0.0
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $helper = Mage::helper('currencymanager');
        $moduleNameId = 'ET_CurrencyManager';

        $moduleVersion = $this->_getConfigValue($moduleNameId, 'version');
        $moduleName = $this->_getConfigValue($moduleNameId, 'name');
        $moduleShortDescription = $this->_getConfigValue($moduleNameId, 'descr');
        $moduleLicense = $this->_getConfigValue($moduleNameId, 'license');

        $linkParameters = '?module=' . $moduleNameId . '&ver=' . $moduleVersion . '&ref=' . $_SERVER['HTTP_HOST'];
        $moduleLicenseLink = $this->_getConfigValue($moduleNameId, 'licenselink') . $linkParameters;
        $moduleSupportLink = $this->_getConfigValue($moduleNameId, 'redminelink') . $linkParameters;
        $moduleLink = $this->_getConfigValue($moduleNameId, 'permanentlink') . $linkParameters;
        $servicesLink = $this->_getConfigValue($moduleNameId, 'ourserviceslink') . $linkParameters;

        $html = 
            '<style>
                .line {border-top: 1px solid #c6c6c6; }
                .developer-label {color: #000000; font-weight:bold; width: 150px;}
                .developer-text { padding-bottom: 15px;}
                .developer {width: 600px; }
            </style>';

        $html .= '
            <table cellspacing="0" cellpading="0" class="developer">
                <tr>
                    <td class="developer-label">' . $helper->__('Extension:') . '</td>
                    <td class="developer-text">' . $helper->__(
                        '<strong>%s</strong> (version %s)',
                        $moduleName,
                        $moduleVersion
                        ) . '</td>
                </tr>
                <tr>
                    <td class="developer-label">' . $helper->__('License:') . '</td>
                    <td class="developer-text">' . $helper->__(
                        '<a href="%s" target="_blank">%s</a>',
                        $moduleLicenseLink,
                        $moduleLicense
                        ) . '</td>
                </tr>
                <tr>
                    <td class="developer-label">' . $helper->__('Short Description:') . '</td>
                    <td class="developer-text">' .$moduleShortDescription. '</td>
                </tr>
                <tr>
                    <td class="developer-label">' . $helper->__('Documentation:') . '</td>
                    <td class="developer-text">' . $helper->__(
                        'You can see description of extension features and answers to the ' .
                        'frequently asked questions on <a href="%s" target="_balnk">our website</a>.',
                        $moduleLink) . '</td>
                </tr>
                <tr>
                    <td class="developer-label line">' . $helper->__('Support:') . '</td>
                    <td class="developer-text line">' . $helper->__(
                        'Extension support is available through <a href="%s" target="_blank">issue tracking system' .
                        '</a>.<br>You can see information freely, but you will have to sign up to open a ticket.<br>' .
                        '<br>Please, report all bugs and feature requests that are connected to this extension.<br>' .
                        '<br>If by some reason you can not submit a question, bug report or feature request to our ' .
                        'ticket system, you can write us an email - support@etwebsolutions.com.',
                        $moduleSupportLink) . '</td>
                </tr>
                <tr>
                    <td class="developer-label line">' . $helper->__('Advertisement:') . '</td>
                    <td class="developer-text line">' . $helper->__(
                        'You can hire our team to customize the extension. E-mail us on sales@etwebsolutions.com.<br>' .
                        '<br>You can see a list of provided services on <a href="%s" target="_blank">our website</a>.',
                        $servicesLink) . '</td>
                </tr>
            </table>';


        return $html;
    }

    protected function _getConfigValue($module, $config)
    {
        $locale = Mage::app()->getLocale()->getLocaleCode();
        $defaultLocale = 'en_US';
        $mainConfig = Mage::getConfig();
        $moduleConfig = $mainConfig->getNode('modules/' . $module . '/' . $config);

        if ((string)$moduleConfig) {
            return $moduleConfig;
        }

        if ($moduleConfig->$locale) {
            return $moduleConfig->$locale;
        } else {
            return $moduleConfig->$defaultLocale;
        }
    }

}