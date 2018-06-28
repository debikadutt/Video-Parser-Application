<?php
/**
 *  Pre-process Data
 *
 *  Author: Debika Dutt debikadu@buffalo.edu
 *  Date:   6/15/2018
 */

class PreprocessData
{
    /**
     *  Display the revenue made from video sales of valid IDs
     *
     *  @param $csv_file     string CSV file_name
     *  @param $is_formatted bool   flag to indicate whether to expect formatted data
     *
     *  @return array Array contains csv data
     */
    public function convert_csv_to_array($csv_file, $is_formatted) {
        $csv = [];
        $filename = './'. $csv_file;
        if (file_exists($filename) && filesize($filename) > 0) {
            $csv = array_map('str_getcsv', file($filename));
            if (!empty($csv)) {
                // flag to check requested columns present in file
                if (!$is_formatted) {
                    // eliminates duplicate columns
                    return array_unique(($csv[0]));
                } else {
                    array_walk($csv, function(&$a) use ($csv) {
                        $a = array_combine($csv[0], $a);
                    });
                    array_shift($csv); # remove column header
                }
            } else {
                echo 'The file could not be read successfully.';
            }
        } else {
            echo 'File does not exist or may not have content' . PHP_EOL;
        }
        return $csv;
    }

    /**
     *  Fetch the requested video data from csv into $videos array
     *
     *  @param $video_file string Name of the input file
     *
     *  @return array Videos array containing processed data
     */
    public function getRequiredColumns($video_file) {
        $videos = [];
        if (!empty($video_file)) {
            $output = $this->convert_csv_to_array($video_file, true);
            // retrieve only the relevant columns, if there were more than 1000 columns, this will add speed
            foreach ($output as $data) {
                $input['id'] = $data['id'];
                $input['title'] = $data['title'];
                $input['total_likes'] = $data['total_likes'];
                $input['total_purchases'] = $data['total_purchases'];
                $input['unit_price_in_usd'] = $data['unit_price_in_usd'];
                array_push($videos, $input);
            }
        }
        return $videos;
    }

    /**
     *  Fetch the exchange rates data from csv into $exchange_rates array
     *
     *  @param $exchange_rate_file string Name of the input file
     *
     *  @return array Videos array containing processed data
     */
    public function getRequiredExchangeRates($exchange_rate_file) {
        $exchange_rates = [];
        if (!empty($exchange_rate_file)) {
            $output = $this->convert_csv_to_array($exchange_rate_file, true);
            foreach ($output as $data) {
                if ($data['currency'] == 'EUR') {
                    $exchange_rates['EUR'] = intval($data['exchange_rate_from_usd']);
                }
                if ($data['currency'] == 'CAD') {
                    $exchange_rates['CAD'] = intval($data['exchange_rate_from_usd']);
                }
            }
        }
        return $exchange_rates;
    }

    /**
     *  Check if the requested columns are in the input file
     *
     *  @param $input_file string Name of the input file
     *
     *  @return bool Return true if diff is empty
     */
    public function isRequiredColumnsPresent($input_file) {
        $diff = [];
        if (!empty($input_file)) {
            $file = $this->convert_csv_to_array($input_file, false);
            $required_columns = ['id', 'title', 'total_likes', 'total_purchases', 'unit_price_in_usd'];
            // check if the required columns exists in the file
            // $diff stores the missing columns
            $diff = array_diff($required_columns, $file);
        }
        return empty($diff);
    }
}