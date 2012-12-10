var globalCutZeroSignPrice = true;
if (typeof(Product) != "undefined") {
    Product.OptionsPrice.prototype = Object.extend(Product.OptionsPrice.prototype, {formatPrice:function (price) {
        var tmpPriceFormat = Object.clone(this.priceFormat);
        if (price - parseInt(price) == 0) {
            tmpPriceFormat.precision = 0;
            tmpPriceFormat.requiredPrecision = 0;
        }
        if (tmpPriceFormat.precision < 0) {
            tmpPriceFormat.precision = 0;
        }
        if (tmpPriceFormat.requiredPrecision < 0) {
            tmpPriceFormat.requiredPrecision = 0;
        }
        var price2return = formatCurrency(price, tmpPriceFormat);
        return price2return;
    }});
}