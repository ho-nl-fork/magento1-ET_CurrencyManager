if (typeof(Product) != "undefined") {
    Product.Config.prototype = Object.extend(Product.Config.prototype, {
        formatPrice:function (price, showSign) {
            var str = '';
            price = parseFloat(price);
            if (showSign) {
                if (price < 0) {
                    str += '-';
                    price = -price;
                }
                else {
                    str += '+';
                }
            }

            var roundedPrice = (Math.round(price * 100) / 100).toString();

            if (this.prices && this.prices[roundedPrice]) {
                str += this.prices[roundedPrice];
            }
            else {
                precision = 2;
                if (typeof(etCurrencyManagerJsConfig) != "undefined") {
                    if (typeof(etCurrencyManagerJsConfig.precision) != "undefined") {
                        precision = etCurrencyManagerJsConfig.precision;
                    }
                }
                if (typeof(optionsPrice) != "undefined") {
                    if (typeof(optionsPrice.priceFormat) != "undefined") {
                        precision = optionsPrice.priceFormat.requiredPrecision;
                    }
                }
                if (typeof(etCurrencyManagerJsConfig) != "undefined") {
                    if (typeof(etCurrencyManagerJsConfig.cutzerodecimal) != "undefined") {
                        if (etCurrencyManagerJsConfig.cutzerodecimal != 0) {
                            if (price - Math.round(price) == 0) {
                                precision = 0;
                            }
                        }
                    }
                }
                if (precision > 0) {
                    str += this.priceTemplate.evaluate({price:price.toFixed(precision)});
                }
                else {
                    price = price.toFixed(0);
                    if (typeof(etCurrencyManagerJsConfig) != "undefined") {
                        if (typeof(etCurrencyManagerJsConfig.cutzerodecimal) != "undefined") {
                            if (etCurrencyManagerJsConfig.cutzerodecimal != 0) {
                                if (typeof(etCurrencyManagerJsConfig.cutzerodecimal_suffix) != "undefined") {
                                    if (etCurrencyManagerJsConfig.cutzerodecimal_suffix.length > 0) {
                                        price = price + "" + etCurrencyManagerJsConfig.cutzerodecimal_suffix;
                                    }
                                }
                            }
                        }
                    }
                    str += this.priceTemplate.evaluate({price:price});
                }
            }
            return str;
        }


    });
}


try {

    originalFormatCurrency = window.formatCurrency;

    window.formatCurrency = function (price, format, showPlus) {
        //zeroSymbol
        //JS round fix
        price = Math.round(price * Math.pow(10, format.precision)) / Math.pow(10, format.precision);
        if (price - Math.round(price) != 0) {
            if (Math.abs(price - Math.round(price)) < 0.00000001) {
                price = 0;
            }
        }
        if (typeof(etCurrencyManagerJsConfig) != "undefined") {
            if (price == 0) {
                if (typeof(etCurrencyManagerJsConfig.zerotext) != "undefined") {
                    return etCurrencyManagerJsConfig.zerotext;
                }
            }
        }
        //cut zero decimal
        if (price - Math.round(price) == 0) {
            if (typeof(etCurrencyManagerJsConfig) != "undefined") {
                if (typeof(etCurrencyManagerJsConfig.cutzerodecimal) != "undefined") {
                    if (etCurrencyManagerJsConfig.cutzerodecimal != 0) {
                        format.precision = 0;
                        format.requiredPrecision = 0;

                        var for_replace = originalFormatCurrency(price, format, showPlus);

                        if (typeof(etCurrencyManagerJsConfig.cutzerodecimal_suffix) != "undefined") {
                            if (etCurrencyManagerJsConfig.cutzerodecimal_suffix.length > 0) {
                                return for_replace.replace(price, price + ""
                                    + etCurrencyManagerJsConfig.cutzerodecimal_suffix);
                            }
                        }
                    }
                }
            }
        }

        return formatCurrencyET(price, format, showPlus);
        //if(format.precision<0)format.precision=0;
        //if(format.requiredPrecision<0)format.requiredPrecision=0;
        /*return originalFormatCurrency(price, format, showPlus);*/


    }


    function formatCurrencyET(price, format, showPlus) {
        var precision = isNaN(format.precision = (format.precision)) ? 2 : format.precision;
        var requiredPrecision = isNaN(format.requiredPrecision = (format.requiredPrecision)) ? 2 : format.requiredPrecision;

        //precision = (precision > requiredPrecision) ? precision : requiredPrecision;
        //for now we don't need this difference so precision is requiredPrecision
        precision = requiredPrecision;

        var integerRequired = isNaN(format.integerRequired = Math.abs(format.integerRequired)) ? 1 : format.integerRequired;

        var decimalSymbol = format.decimalSymbol == undefined ? "," : format.decimalSymbol;
        var groupSymbol = format.groupSymbol == undefined ? "." : format.groupSymbol;
        var groupLength = format.groupLength == undefined ? 3 : format.groupLength;

        var s = '';

        if (showPlus == undefined || showPlus == true) {
            s = price < 0 ? "-" : ( showPlus ? "+" : "");
        } else if (showPlus == false) {
            s = '';
        }

        var i = parseInt(price = Math.abs(+price || 0).toFixed(precision)) + "";
        var pad = (i.length < integerRequired) ? (integerRequired - i.length) : 0;
        while (pad) {
            i = '0' + i;
            pad--;
        }
        j = (j = i.length) > groupLength ? j % groupLength : 0;
        re = new RegExp("(\\d{" + groupLength + "})(?=\\d)", "g");

        /**
         * replace(/-/, 0) is only for fixing Safari bug which appears
         * when Math.abs(0).toFixed() executed on "0" number.
         * Result is "0.-0" :(
         */
        if (precision < 0) {
            precision = 0;
        }
        var r = (j ? i.substr(0, j) + groupSymbol : "") + i.substr(j).replace(re, "$1" + groupSymbol) + (precision ? decimalSymbol + Math.abs(price - i).toFixed(precision).replace(/-/, 0).slice(2) : "")
        var pattern = '';
        if (format.pattern.indexOf('{sign}') == -1) {
            pattern = s + format.pattern;
        } else {
            pattern = format.pattern.replace('{sign}', s);
        }

        return pattern.replace('%s', r).replace(/^\s\s*/, '').replace(/\s\s*$/, '');
    }

    ;


}
catch (e) {
    //do nothing
}