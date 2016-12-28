<?php

$input = array(0, 0.01, 0.001, 0.99, 5.49, 5.5, 1222.49, 1222.9999, 1222.4449, 1222.4499, 33,
    123456789, -0.01, -0.001, -0.99, -5.49, -5.5, -1222.49, -1222.9999, -1222.4449,
    -1222.4499, -33, -123456789);

//$precisions = array(-1, 0, 1, 2, 3, 4);
$precisions = array(0, 1, 2, 3, 4);
$minimalPrecisions = array(0, 1, 2, 3, 4);
$cutZeroDecimals = array(0, 1);
$cutZeroDecimalSuffixes = array("", "text", "<b>html</b>");
$zeroTexts = array("", "text", "<b>html</b>");

//no(1), Symbol(2), Short Name(3), Name(4)
$displaySymbols = array(1 => "", 2 => "$", 3 => "USD", 4 => "US dollars");
$displaySymbolsForOne = "US dollar";

$decimalPoint = ".";
$thousandSeparator = ",";

//default(8), left(16), right(32)
$symbolPositions = array(16, 32);

//$symbolReplace    = array(0, 1);

$testFormat = array();
$testFormatYaml = array();
$testCsvFormat = array(array("result", "value", "precision", "min_decimal_count", "cutzerodecimal",
    "cutzerodecimal_suffix", "zerotext",
    "display", "position"));

foreach ($precisions as $precision) {
    foreach ($minimalPrecisions as $minimalPrecision) {
        foreach ($cutZeroDecimals as $cutzerodecimal) {
            foreach ($cutZeroDecimalSuffixes as $cutzerodecimalSuffix) {
                foreach ($zeroTexts as $zerotext) {
                    foreach ($displaySymbols as $displaySymbol => $displaySymbolText) {
                        foreach ($symbolPositions as $symbolPosition) {
                            foreach ($input as $value) {
                                //precision
                                $test = round($value, $precision);
                                if ($minimalPrecision < $precision) {
                                    for ($testMP = $precision - 1; $testMP >= $minimalPrecision; $testMP--) {
                                        if (abs($test - round($test, $testMP)) < 0.00001) {
                                            $test = round($test, $testMP);
                                        }
                                    }
                                }
                                if ($cutzerodecimal && ($test === round($test, 0))) {
                                    $test = sprintf("%.0f", $test) . $cutzerodecimalSuffix;
                                } else {
                                    //$test = $precision>0?sprintf("%." . $precision . "f", $test):sprintf("%d", $test);
                                    $test = number_format($test, max(0, $precision), $decimalPoint, $thousandSeparator);
                                }
                                if (($zerotext != "") && (round($value, $precision) == 0)) {
                                    $test = $zerotext;
                                } else {
                                    if ($displaySymbolText != "") {
                                        $finalDisplaySymbolText = $displaySymbolText;
                                        //spec dlja 1 dollara
                                        if ((round($value, $precision) == 1) && ($displaySymbol == 4)) {
                                            $finalDisplaySymbolText = $displaySymbolsForOne;
                                            //exit;
                                        }
                                        $imploder = " ";
                                        if ($displaySymbol == 2) {
                                            $imploder = "";
                                        }
                                        if ($symbolPosition == 32) {
                                            $test = $finalDisplaySymbolText . $imploder . $test;
                                        } else {
                                            $test = $test . $imploder . $finalDisplaySymbolText;
                                        }
                                    }
                                }

                                //if (round($value, $precision) && ($displaySymbol == 4)){
                                //}

                                $code = $value . "-" . $precision . "-" . $minimalPrecision
                                    . "-" . $cutzerodecimal . "-"
                                    . $cutzerodecimalSuffix . "-" . $zerotext . "-" . $displaySymbol
                                    . "-" . $symbolPosition;
                                $testFormat[] = $code . ":\r\n formatted_number: " . $test
                                    . "\r\n formatted_string: |\r\n  " . $test;
                                $testFormatYaml[] = "-\r\n - {precision: " . $precision . ", min_decimal_count: "
                                    . $minimalPrecision . ", cutzerodecimal: "
                                    . $cutzerodecimal . ", cutzerodecimal_suffix: " . $cutzerodecimalSuffix
                                    . ", zerotext: " . $zerotext . ", display: " . $displaySymbol
                                    . ", position: " . $symbolPosition . "}\r\n - " . $value;
                                $testCsvFormat[] = array($test, $value, $precision, $minimalPrecision, $cutzerodecimal
                                , $cutzerodecimalSuffix, $zerotext, $displaySymbol, $symbolPosition);

                            }
                        }
                    }
                }
            }
        }
    }
}

file_put_contents("expectations/testFormat.yaml", implode("\r\n", $testFormat));
file_put_contents("providers/testFormat.yaml", implode("\r\n", $testFormatYaml));

$fp = fopen("testFormat.csv", "wb");
foreach ($testCsvFormat as $zzz => $testCsvString) {
    fputcsv($fp, $testCsvString);
}

fclose($fp);

//print_r($testCsvFormat);