<?php
class ET_CurrencyManager_Test_Model_Store extends EcomDev_PHPUnit_Test_Case
{
    /*
    * Product price round test
    *
    * @test
    */
    public function testRoundPrice($price)
    {
        $model = Mage::getModel('core/store');
        $this->assertInstanceOf('ET_CurrencyManager_Model_Store', $model);
        Mage::app()->getStore()->setConfig('currencymanager/general/precision', 3);
        $this->assertEquals($model->roundPrice(10), 10);
        $this->assertEquals($model->roundPrice(10.9), 10.90);
        $this->assertEquals($model->roundPrice(10.142), 10.14, '');
        $this->assertEquals($model->roundPrice(10.145), 10.15);
        $this->assertEquals($model->roundPrice(10.148), 10.15);
    }
}