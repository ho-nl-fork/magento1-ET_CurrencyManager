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

class ET_CurrencyManager_Block_Adminhtml_Symbolreplace extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected $_addRowButtonHtml = array();
    protected $_removeRowButtonHtml = array();

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $html = '<div id="symbolreplace_template" style="display:none">';
        $html .= $this->_getRowTemplateHtml();
        $html .= '</div>';

        $html .= '<ul id="symbolreplace_container">';
        if ($this->_getValue('currency')) {
            foreach (array_keys($this->_getValue('currency')) as $row) {
                if ($row) {
                    $html .= $this->_getRowTemplateHtml($row);
                }
            }
        }
        $html .= '</ul>';
        $html .= $this->_getAddRowButtonHtml(
            'symbolreplace_container',
            'symbolreplace_template', $this->__('Add currency specific options')
        );

        return $html;
    }

    protected function _getRowTemplateHtml($row = 0)
    {
        $html = '<li><fieldset>';
        $html .= $this->_getCurrencySelectHtml($row);
        $html .= $this->_getPrecisionHtml($row);
        $html .= $this->_getMinDecimalCountHtml($row);
        $html .= $this->_getCutZeroHtml($row);
        $html .= $this->_getSuffixHtml($row);
        $html .= $this->_getSymbolPositionHtml($row);
        $html .= $this->_getSymbolUseHtml($row);
        $html .= $this->_getSymbolReplaceHtml($row);
        $html .= $this->_getZeroPriceReplaceHtml($row);

        $html .= '<br /> <br />';
        $html .= $this->_getRemoveRowButtonHtml();
        $html .= '</fieldset></li>';

        return $html;
    }

    protected function _getZeroPriceReplaceHtml($row)
    {
        $html = '<label>' . $this->__('Replace Zero Price to:') . ' </label> ';
        $html .= '<input class="input-text" name="' . $this->getElement()->getName() . '[zerotext][]" value="'
            . $this->_getValue('zerotext/' . $row) . '" ' . $this->_getDisabled() . '/> ';
        $html .= '<p class="nm"><small>' . $this->__('Leave empty for global value use') . '</small></p>';
        return $html;
    }

    protected function _getSymbolReplaceHtml($row)
    {
        $html = '<label>' . $this->__('Replace symbol to:') . ' </label> ';
        $html .= '<input class="input-text" name="' . $this->getElement()->getName() . '[symbol][]" value="'
            . $this->_getValue('symbol/' . $row) . '" ' . $this->_getDisabled() . '/> ';
        $html .= '<p class="nm"><small>' . $this->__('Leave empty for disable replace') . '</small></p>';
        return $html;
    }

    protected function _getSymbolUseHtml($row)
    {
        $html = '<label>' . $this->__('Currency symbol use:') . ' </label> ';
        $html .= '<select class="input-text" name="' . $this->getElement()->getName() . '[display][]">';
        foreach (Mage::getModel("currencymanager/typesymboluse")->toOptionArray() as $labelValue) {
            $html .= '<option value="' . $labelValue["value"] . '" '
                . ($this->_getValue('display/' . $row) == $labelValue["value"] ? 'selected="selected"' : '') . '>'
                . $labelValue["label"] . "</option>";
        }
        $html .= '</select>';
        return $html;
    }

    protected function _getSymbolPositionHtml($row)
    {
        $html = '<label>' . $this->__('Symbol position:') . ' </label> ';
        $html .= '<select class="input-text" name="' . $this->getElement()->getName() . '[position][]">';
        foreach (Mage::getModel("currencymanager/typeposition")->toOptionArray() as $labelValue) {
            $html .= '<option value="' . $labelValue["value"] . '" '
                . ($this->_getValue('position/' . $row) == $labelValue["value"] ? 'selected="selected"' : '') . '>'
                . $labelValue["label"] . "</option>";
        }
        $html .= '</select>';
        return $html;
    }

    protected function _getSuffixHtml($row)
    {
        $html = '<label>' . $this->__('Suffix:') . ' </label> ';
        $html .= '<input class="input-text" name="' . $this->getElement()->getName()
            . '[cutzerodecimal_suffix][]" value="' . $this->_getValue('cutzerodecimal_suffix/' . $row)
            . '" ' . $this->_getDisabled() . '/> ';
        $html .= '<p class="nm"><small>' . $this->__('Leave empty for global value use') . '</small></p>';
        return $html;
    }

    protected function _getMinDecimalCountHtml($row)
    {
        $html = '<label>' . $this->__('Minimum number of digits after the decimal point:') . ' </label> ';
        $html .= '<input class="input-text" name="' . $this->getElement()->getName()
            . '[min_decimal_count][]" value="' . $this->_getValue('min_decimal_count/' . $row)
            . '" ' . $this->_getDisabled() . '/> ';
        $html .= '<p class="nm"><small>' . $this->__('Leave empty for global value use') . '</small></p>';
        return $html;
    }

    protected function _getCutZeroHtml($row)
    {
        $html = '<label>' . $this->__('Cut Zero Decimals:') . ' </label> ';
        $html .= '<select class="input-text" name="' . $this->getElement()->getName() . '[cutzerodecimal][]">';
        foreach (Mage::getModel("adminhtml/system_config_source_yesno")->toOptionArray() as $labelValue) {
            $html .= '<option value="' . $labelValue["value"] . '" '
                . ($this->_getValue('cutzerodecimal/' . $row) == $labelValue["value"] ? 'selected="selected"' : '')
                . '>' . $labelValue["label"] . "</option>";
        }
        $html .= '</select>';
        return $html;
    }

    protected function _getPrecisionHtml($row)
    {
        $html = '<label>' . $this->__('Display precision:') . ' </label> ';
        $html .= '<input class="input-text" name="' . $this->getElement()->getName() . '[precision][]" value="'
            . $this->_getValue('precision/' . $row) . '" ' . $this->_getDisabled() . '/> ';
        $html .= '<p class="nm"><small>' . $this->__('Leave empty for global value use') . '</small></p>';
        return $html;
    }

    protected function _getCurrencySelectHtml($row)
    {
        $html = '<label>' . $this->__('Select currency:') . ' </label> ';

        $html .= '<select name="' . $this->getElement()->getName() . '[currency][]" ' . $this->_getDisabled() . '>';

        $html .= '<option value="">' . $this->__('* Select currency') . '</option>';
        foreach ($this->getAllowedCurrencies() as $currencyCode => $currency) {
            $html .= '<option value="' . $currencyCode . '" ' . $this->_getSelected('currency/' . $row, $currencyCode)
                . ' style="background:white;">' . $currency . " - " . $currencyCode . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    protected function getAllowedCurrencies()
    {

        if (!$this->hasData('allowed_currencies')) {
            $currencies = Mage::app()->getLocale()->getOptionCurrencies();
            $allowedCurrencyCodes = Mage::getSingleton('directory/currency')->getConfigAllowCurrencies();

            $formattedCurrencies = array();
            foreach ($currencies as $currency) {
                $formattedCurrencies[$currency['value']]['label'] = $currency['label'];
            }

            $allowedCurrencies = array();
            foreach ($allowedCurrencyCodes as $currencyCode) {
                $allowedCurrencies[$currencyCode] = $formattedCurrencies[$currencyCode]['label'];
            }

            $this->setData('allowed_currencies', $allowedCurrencies);
        }
        return $this->getData('allowed_currencies');
    }

    protected function _getDisabled()
    {
        return $this->getElement()->getDisabled() ? ' disabled' : '';
    }

    protected function _getValue($key)
    {
        $value = $this->getElement()->getData('value/' . $key);
        if (is_null($value) && $key != 'currency') {
            $key = explode("/", $key);
            $key = array_shift($key);
            //$value = Mage::app()->getConfig()->getNode('default/currencymanager/general/symbolreplace/'.$key);
            $value = Mage::app()->getConfig()->getNode('default/currencymanager/general/'.$key);
            return (string)$value;
        }
        return $value;
    }

    protected function _getSelected($key, $value)
    {
        return $this->getElement()->getData('value/' . $key) == $value ? 'selected="selected"' : '';
    }

    protected function _getAddRowButtonHtml($container, $template, $title = 'Add')
    {
        if (!isset($this->_addRowButtonHtml[$container])) {
            $this->_addRowButtonHtml[$container] = $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setType('button')
                ->setClass('add ' . $this->_getDisabled())
                ->setLabel($this->__($title))
            //$this->__('Add')
                ->setOnClick("Element.insert($('" . $container . "'), {bottom: $('" . $template . "').innerHTML})")
                ->setDisabled($this->_getDisabled())
                ->toHtml();
        }
        return $this->_addRowButtonHtml[$container];
    }

    protected function _getRemoveRowButtonHtml($selector = 'li', $title = 'Remove')
    {
        if (!$this->_removeRowButtonHtml) {
            $this->_removeRowButtonHtml = $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setType('button')
                ->setClass('delete v-middle ' . $this->_getDisabled())
                ->setLabel($this->__($title))
            //$this->__('Remove')
                ->setOnClick("Element.remove($(this).up('" . $selector . "'))")
                ->setDisabled($this->_getDisabled())
                ->toHtml();
        }
        return $this->_removeRowButtonHtml;
    }
}