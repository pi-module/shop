<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * CSVToExcelConverter
 * https://www.phpclasses.org/package/7528-PHP-Convert-CSV-files-to-Excel-using-PHPExcel-library.html
 */

namespace Module\Shop\Api;

use Pi;
use Pi\Application\Api\AbstractApi;

/*
 * Pi::api('CSVToExcelConverter', 'shop')->convert($csv_file, $xls_file, $csv_enc);
 * Pi::api('CSVToExcelConverter', 'shop')->check();
 */

class CSVToExcelConverter extends AbstractApi
{
    /**
     * Read given csv file and write all rows to given xls file
     * Get php excel from https://github.com/PHPOffice/PHPExcel
     *
     * @param string $csv_file Resource path of the csv file
     * @param string $xls_file Resource path of the excel file
     * @param string $csv_enc  Encoding of the csv file, use utf8 if null
     *
     * @throws Exception
     */
    public function convert($csv_file, $xls_file, $csv_enc = null)
    {
        // Load PHPExcel class
        require_once Pi::path('vendor') . '/Excel/PHPExcel.php';

        //set cache
        $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        \PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

        //open csv file
        $objReader = new \PHPExcel_Reader_CSV();
        if ($csv_enc != null) {
            $objReader->setInputEncoding($csv_enc);
        }
        $objPHPExcel = $objReader->load($csv_file);
        $in_sheet    = $objPHPExcel->getActiveSheet();

        //open excel file
        $objPHPExcel = new \PHPExcel();
        $out_sheet   = $objPHPExcel->getActiveSheet();

        //row index start from 1
        $row_index = 0;
        foreach ($in_sheet->getRowIterator() as $row) {
            $row_index++;
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            //column index start from 0
            $column_index = -1;
            foreach ($cellIterator as $cell) {
                $column_index++;
                $out_sheet->setCellValueByColumnAndRow($column_index, $row_index, $cell->getValue());
            }
        }

        //write excel file
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save($xls_file);
    }

    public function check()
    {
        $file = Pi::path('vendor') . '/Excel/PHPExcel.php';
        if (Pi::service('file')->exists($file)) {
            return true;
        }
        return false;
    }
}
