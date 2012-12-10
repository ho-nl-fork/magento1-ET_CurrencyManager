<?php

$input = array(0, 0.01, 0.001, 0.99, 5.49, 5.5, 1222.49, 1222.9999, 1222.4449, 1222.4499, 33, 
                123456789, -0.01, -0.001, -0.99, -5.49, -5.5, -1222.49, -1222.9999, -1222.4449, 
                -1222.4499, -33, -123456789);

//$precisions = array(-1, 0, 1, 2, 3, 4);
$precisions = array(0, 1, 2, 3, 4);
$cutzerodecimals = array(0, 1);
$cutzerodecimalSuffixes = array("", "text", "<b>html</b>");
$zerotexts = array("", "text", "<b>html</b>");

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
$testCsvFormat = array(array("result","value", "precision", "cutzerodecimal", "cutzerodecimal_suffix", "zerotext",
                        "display", "position"));

foreach ($precisions as $precision) {
    foreach ($cutzerodecimals as $cutzerodecimal) {
        foreach ($cutzerodecimalSuffixes as $cutzerodecimalSuffix) {
            foreach ($zerotexts as $zerotext) {
                foreach ($displaySymbols as $displaySymbol => $displaySymbolText) {
                    foreach ($symbolPositions as $symbolPosition) {
                        foreach ($input as $value) {
                            //precision
                            $test = round($value, $precision);
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
                                    $imploder=" ";
                                    if ($displaySymbol == 2) {
                                        $imploder="";
                                    }
                                    if ($symbolPosition == 16) {
                                        $test = $finalDisplaySymbolText . $imploder . $test;
                                    } else {
                                        $test = $test . $imploder . $finalDisplaySymbolText;
                                    }
                                }
                            }

                            //if (round($value, $precision) && ($displaySymbol == 4)){
                            //}

                            $code = $value . "-" . $precision . "-" . $cutzerodecimal . "-" 
                                    . $cutzerodecimalSuffix . "-" . $zerotext . "-" . $displaySymbol 
                                    . "-" . $symbolPosition;
                            $testFormat[] = $code . ":\r\n formatted_number: " . $test 
                                            . "\r\n formatted_string: |\r\n  " . $test;
                            $testFormatYaml[] = "-\r\n - {precision: " . $precision . ", cutzerodecimal: " 
                                                . $cutzerodecimal . ", cutzerodecimal_suffix: " . $cutzerodecimalSuffix
                                                . ", zerotext: " . $zerotext . ", display: " . $displaySymbol 
                                                . ", position: " . $symbolPosition . "}\r\n - " . $value;
                            $testCsvFormat[] = array($test, $value, $precision, $cutzerodecimal, $cutzerodecimalSuffix,
                                                $zerotext, $displaySymbol, $symbolPosition);

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
foreach ($testCsvFormat as $zzz=>$testCsvString) {
    fputcsv($fp, $testCsvString);
}

fclose($fp);

//print_r($testCsvFormat);