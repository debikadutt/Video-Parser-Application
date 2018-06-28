<?php
/**
 *  Filter Class
 *
 *  Author: Debika Dutt debikadu@buffalo.edu
 *  Date:   6/15/2018
 */
//namespace VideoParserApplication;

class Filter
{
    /**
     *  Apply necessary filters on the data
     *
     *  @return array Array containing the filter['key'] => value
     */
    public function applyFilters() {
        $title_length = 30;
        $number_of_likes = 10;
        $total_sales = 200;
        $min_canadian_dollar_price = 25;
        $min_euro_price = 20;
        $filter['title_length'] = $title_length;
        $filter['number_of_likes'] = $number_of_likes;
        $filter['total_sales'] = $total_sales;
        $filter['min_canadian_dollar_price'] = $min_canadian_dollar_price;
        $filter['min_euro_price'] = $min_euro_price;

        return $filter;
    }
}