<?php
namespace frontend\traits;


use PHPExcel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPExcel_Style_NumberFormat;

trait UseDownloadExcel
{

    private function columnLetter($c){

        $c = intval($c);
        $c += 1;
        if ($c <= 0) return '';

        $letter = '';

        while($c != 0){
            $p = ($c - 1) % 26;
            $c = intval(($c - $p) / 26);
            $letter = chr(65 + $p) . $letter;
        }

        return $letter;
    }


    private function downloadExcel($items, $attributes, string $title)
    {

        ini_set("memory_limit", -1);
        ini_set("max_execution_time", "9200");


        $objPHPExcel = new PHPExcel();
        // set document properties
        $objPHPExcel->getProperties()->setTitle($title);
        // create a first sheet and populate the headings
        $objPHPExcel->setActiveSheetIndex(0);


        $i = 0;
        foreach ($attributes as $code => $value) {
            $name = is_array($value) ? $value['name'] : $value;
            $col = $this->columnLetter($i) . 1;
            $objPHPExcel->getActiveSheet()->setCellValue($col, $name);
            $i++;
        }

        $j = 2;
        foreach ($items as $item) {
            $i = 0;
            foreach ($attributes as $code => $value) {
                $col = $this->columnLetter($i) . $j;
                $cell = $objPHPExcel->getActiveSheet()->setCellValue($col, $item->$code, true);

                if(is_array($value)&&isset($value['format'])){
                    $cell->getStyle()->getNumberFormat()->applyFromArray(
                        ['code' => $value['format']]
                    );
                }

                $i++;
            }
            $j++;
        }

        // EXPORT EXCEL TO IMPORT
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $date = gmdate('Y-m-d-His');
        header("Content-Disposition: attachment;filename=\"$title-$date.xlsx\"");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}