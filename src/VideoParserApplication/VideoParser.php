<?php
/**
 *  Video Parser
 *
 *  Author: Debika Dutt debikadu@buffalo.edu
 *  Date:   6/15/2018
 */
require "Filter.php";

class VideoParser
{
    /**
     *  Parse given CSV file into valid.csv and invalid.csv
     *
     * @param $videos         array Preprocessed video data file
     * @param $exchange_rates array Exchange rate data
     *
     *  @return bool Return true if either files valid.csv or invalid.csv was successfully written
     */
    public function parseCsvFile($videos, $exchange_rates)
    {
        // get the exchange rates for the required currencies for later calculations
        $euro = $canadian = 0;
        if (!empty($exchange_rates)) {
            $euro = $exchange_rates['EUR'];
            $canadian = $exchange_rates['CAD'];
        }

        $invalid = $valid = [];
        $total_revenue = 0;
        $is_valid_file_written = $is_invalid_file_written = false;

        /* parse the videos data according to the given rules :
         *
         * 1. The video title must be shorter than 30 characters.
         * 2. The video must have over 10 likes.
         * 3. The video must have over 200 sales.
         * 4. The video price must be under 20 Euros or under 25 Canadian Dollars.
         */
        if (!empty($videos)) {
            // call the Filter class to apply multiple filters, supports extensibility
            $filter = new Filter();
            $filter_arr = $filter->applyFilters();
            // null coalesce isn't working for this version!
            $title_length = isset($filter_arr['title_length']) ? $filter_arr['title_length'] : 0;
            $number_of_likes = isset($filter_arr['number_of_likes']) ? $filter_arr['number_of_likes'] : 0;
            $total_sales = isset($filter_arr['total_sales']) ? $filter_arr['total_sales'] : 0;
            $min_canadian_dollar_price = isset($filter_arr['min_canadian_dollar_price']) ? $filter_arr['min_canadian_dollar_price'] : 0;
            $min_euro_price = isset($filter_arr['min_euro_price']) ? $filter_arr['min_euro_price'] : 0;

            // sort them into valid and invalid records
            foreach($videos as $row) {
                $price_in_euro = $row['unit_price_in_usd'] * $euro;
                $price_in_canadian = $row['unit_price_in_usd'] * $canadian;
                if (strlen($row['title']) < $title_length && intval($row['total_likes']) > $number_of_likes
                    && intval($row['total_purchases']) > $total_sales
                    && ($price_in_canadian < $min_canadian_dollar_price || $price_in_euro < $min_euro_price)) {
                    array_push($valid, $row['id']);
                    // calculate total revenue from valid video ids
                    $total_revenue += intval($row['unit_price_in_usd'] * $row['total_purchases']);
                } else {
                    array_push($invalid, $row['id']);
                }
            }

            // Display the total revenue made from sales of valid videos
            if (!empty($total_revenue)) {
                $this->displayRevenueGenerated($total_revenue);
            }

            // Write data to respective files
            $is_valid_file_written = $this->writeToFile($valid, 'valid.csv');
            $is_invalid_file_written = $this->writeToFile($invalid, 'invalid.csv');
        }

        // check if either of the file exists and has content
        return !empty($is_valid_file_written) || !empty($is_invalid_file_written);
    }

    /**
     *  Write the valid or invalid video IDs onto csv files
     *  @param $content  array  The ID array
     *  @param $filename string The name of the file
     *
     *  @return int | boolean On success returns the number of bytes written or false on failure
     */
    public function writeToFile($content, $filename)
    {
        $success = false;
        if (!empty($content && $filename)) {
            $file = fopen($filename, 'w');
            if (!empty($file)) {
                $success = fputcsv($file, ['IDs']);
                foreach ($content as $row) {
                    $val = [];
                    array_push($val, $row);
                    fputcsv($file, $val);
                }
                fclose($file);
            } else {
                echo 'Could not open given file.';
            }
        }
        return $success;
    }

    /**
     *  Display the revenue made from video sales of valid IDs
     *
     *  @param $total_revenue int The total revenue
     *
     *  @return bool If the value was printed
     */
    public function displayRevenueGenerated($total_revenue)
    {
        $success = false;
        if (!empty($total_revenue)) {
            echo 'Revenue generated from valid videos: ';
            echo '$' . number_format($total_revenue, 2) . PHP_EOL;
            $success = true;
        } else {
            echo 'No revenue generated.';
        }
        return $success;
    }
}