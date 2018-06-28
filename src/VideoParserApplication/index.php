<?php
/**
 *  Index.php
 *
 *  Author: Debika Dutt debikadu@buffalo.edu
 *  Date:   6/15/2018
 */
require "VideoParser.php";
require "PreprocessData.php";

// ask inputs from user
echo 'Please enter a valid file_name to parse: ';
$input_file = rtrim(fgets(STDIN));

echo 'Please enter exchange rate file name: ';
$exchange_rate_file = rtrim(fgets(STDIN));

$parser = new VideoParser();
$preprocessor = new PreprocessData();
// check for all valid inputs before sending the file
$input_file_ext = pathinfo($input_file);
$exchange_rates_file_ext = pathinfo($input_file);
$file_type = 'csv';
// check for valid csv extension
$is_valid_ext = $exchange_rates_file_ext['extension'] == $file_type && $input_file_ext['extension'] == $file_type;
$msg = '';
if (!empty($input_file && $exchange_rate_file) && $is_valid_ext) {
    // check if file has the required columns
    $is_present = $preprocessor->isRequiredColumnsPresent($input_file);
    // continue if there are no missing columns
    if ($is_present) {
        $videos = $preprocessor->getRequiredColumns($input_file);
        $exchange_rates = $preprocessor->getRequiredExchangeRates($exchange_rate_file);

        // feed the videos and exchange rates data to parse_csv_file
        if (!empty($videos && $exchange_rates)) {
            $success = $parser->parseCsvFile($videos, $exchange_rates);
            $msg = !$success ? 'Unable to parse the files.' : 'Successfully written to files.';
        } else {
            $msg = 'Video or exchange rates data are empty.' . PHP_EOL;
        }
    } else {
        $msg = 'All of the requested columns not found.' . PHP_EOL;
    }
} else {
    $msg = 'The given files were not found or are not of type ' . $file_type;
}
echo $msg;





