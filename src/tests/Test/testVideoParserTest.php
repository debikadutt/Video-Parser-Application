<?php
/**
 *  Test VideoParser.php
 *
 *  Author: Debika Dutt debikadu@buffalo.edu
 *  Date:   6/15/2018
 */
namespace tests\Test;
use PHPUnit\Framework\TestCase;

class testVideoParserTest extends TestCase
{
    public function testParseCsvFile() {
        $videos = [
            0 =>
                [
                    'id' => '200019',
                    'title' => 'Drift Day',
                    'total_likes' => '6',
                    'total_purchases' => '205',
                    'unit_price_in_usd' => '5.6',
                ],
            1 =>
                [
                    'id' => '200088',
                    'title' => 'Bloc Party- The Prayer',
                    'total_likes' => '10',
                    'total_purchases' => '751',
                    'unit_price_in_usd' => '0',
                ],
            2 =>
                [
                    'id' => '200111',
                    'title' => '5 Memorial Weekend Vignettes',
                    'total_likes' => '21',
                    'total_purchases' => '689',
                    'unit_price_in_usd' => '17.04',
                ]
        ];

        $exchange_rates = [
            'EUR' => '0.838539',
            'CAD' => '1.25085'
        ];
        $video_parser = new \VideoParser();
        $success = $video_parser->parseCsvFile($videos, $exchange_rates);
        $this->assertTrue($success);
    }

    public function testDisplayRevenueGenerated() {
        $total_revenue = 1000;
        $video_parser = new \VideoParser();
        $success = $video_parser->displayRevenueGenerated($total_revenue);
        $this->assertTrue($success);
    }
}