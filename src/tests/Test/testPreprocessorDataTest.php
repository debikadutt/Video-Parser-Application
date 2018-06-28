<?php
/**
 *  Test PreprocessData.php
 *
 *  Author: Debika Dutt debikadu@buffalo.edu
 *  Date:   6/15/2018
 */
namespace tests\Test;
use PHPUnit\Framework\TestCase;

class testPreprocessorDataTest extends TestCase
{
    public function testIsRequiredColumnsPresent()
    {
        $input_file = '.\VideoParserApplication\test_file_input.csv';
    	$preprocessor = new \PreprocessData();
    	$success = $preprocessor->isRequiredColumnsPresent($input_file);
        $this->assertTrue($success);
    }

    public function testConvertCsvToArray()
    {
        $input_file = '.\VideoParserApplication\test_file_input.csv';
        $preprocessor = new \PreprocessData();
        $csv = [];
        if (file_get_contents($input_file)) {
            $csv = $preprocessor->convert_csv_to_array($input_file, true);
        }
        $required_columns = [
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
        ];
        $this->assertSame($csv, $required_columns);
    }

    public function testGetRequiredColumns()
    {
        $input_file = '.\VideoParserApplication\fetch_cols_file.csv';
        $preprocessor = new \PreprocessData();
        $videos = $preprocessor->getRequiredColumns($input_file);
        $required_columns = [
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
        ];
        $this->assertSame($videos, $required_columns);
    }
}
