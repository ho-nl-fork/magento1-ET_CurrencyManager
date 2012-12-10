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

//if (!class_exists('Mage_Adminhtml_Block_System_Config_Form_Field_Heading')) {
// https://bugs.php.net/bug.php?id=52339
if (version_compare(Mage::getVersion(), '1.4.1', '<')) {
    class ET_CurrencyManager_Block_Adminhtml_Heading
        extends Mage_Adminhtml_Block_Abstract
            implements Varien_Data_Form_Element_Renderer_Interface
    {

        public function render(Varien_Data_Form_Element_Abstract $element)
        {
            return sprintf(
                '<tr class="system-fieldset-sub-head" id="row_%s"><td colspan="5"><h4 id="%s">%s</h4></td></tr>',
                $element->getHtmlId(), $element->getHtmlId(), $element->getLabel()
            );
        }
    }
} else {
    class ET_CurrencyManager_Block_Adminhtml_Heading extends Mage_Adminhtml_Block_System_Config_Form_Field_Heading
    {
    }
}