<?php
/**
 * User: teeeb
 * Date: 2016. 01. 12.
 * Time: 14:57
 */

namespace Calendar\Helper;


use decoy\log\Logger;
use PHPExcel_Style;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;

/**
 * Class WorkTimeMonth
 * @package src\Calendar\Helper
 */
class WorkTimeMonth
{
    /**
     * @var array
     */
    private $styleSettings = array(
        'timelabel' => array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb' => 'A4C2F4')
            )
        ),
        'sumlabel' => array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb' => 'FFE599')
            )
        ),
        'datelabel' => array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb' => '93C47D')
            ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders'=>array(
                'allborders'=>array(
                    'style'=>PHPExcel_Style_Border::BORDER_THIN,
                    'color'=>array('argb'=>'000000')
                )
            )

        ),
        'black' => array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('argb' => '434343')
            ),
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => 'FFFFFF'),
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => 'FFFFFF')
                )
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        ),
        'typeLabel'=>array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        )
    );


    /**
     * @var WorkTimeDay[]
     */
    private $days = array();
    /**
     * @var PHPExcel_Style
     */
    private $labelStyle;
    /**
     * @var PHPExcel_Style
     */
    private $timeStyle;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var
     */
    private $dayTypeSettings;

    /**
     * WorkTimeMonth constructor.
     */
    public function __construct(\DateTime $date)
    {
        $this->labelStyle = new PHPExcel_Style();
        $this->labelStyle->applyFromArray($this->styleSettings['timelabel']);
        $this->timeStyle = new PHPExcel_Style();
        $this->timeStyle->applyFromArray($this->styleSettings['sumlabel']);

        $this->date = $date;
    }

    /**
     * @param array $dayTypeSettings
     */
    public function setDayTypeSettings($dayTypeSettings)
    {
        $this->dayTypeSettings = $dayTypeSettings;
    }

    /**
     * @param $id
     * @return string
     */
    private function getTypeLabel($id){
        foreach($this->dayTypeSettings as $dt){
            if($dt['entryTypeId']==$id)
                return $dt['entryName'];
        }
        return 'N/A';
    }

    /**
     * @param $id
     * @return null
     */
    private function getType($id){
        foreach ($this->dayTypeSettings as $dayTypeSetting) {
            if($dayTypeSetting['entryTypeId']==$id)
                return $dayTypeSetting;
        }
        return null;
    }

    /**
     * @param WorkTimeDay $day
     */
    public function addDay(WorkTimeDay $day)
    {
        $this->days[] = $day;
    }

    /**
     * @param \PHPExcel $sheet
     */
    public function render(\PHPExcel $sheet)
    {
        $logger = new Logger();
        $rowStart = 5;
        $startFrom = $rowStart;
        $page = $sheet->getActiveSheet();

        $page->mergeCells('A1:B1');
        $page->getColumnDimension('A')->setWidth(15);
        $page->setCellValue('A1','Tálosi Tibor');

        $page->mergeCells('A2:C2');
        $page->setCellValue('A2', 'Jelenléti ív: '.$this->date->format('Y').'. év '.$this->date->format('m').'. hó');


        for($i=0;$i<count(WorkTimeDay::$dayMap);$i++){
            $col1 = WorkTimeMonth::getNameFromNumber($i*2+1);
            $col2 = WorkTimeMonth::getNameFromNumber($i*2+2);
            $page->setCellValue($col1 . '4', 'Idő');
            $page->getCell($col1 . '4')->getStyle()->applyFromArray($this->styleSettings['black']);
            $page->getColumnDimension($col1)->setWidth(15);
            $page->setCellValue($col2 . '4', 'Óra');
            $page->getCell($col2 . '4')->getStyle()->applyFromArray($this->styleSettings['black']);
            $page->mergeCells($col1 . '3:' . $col2 . '3');
            $page->setCellValue($col1 . '3', $this->getTypeLabel(WorkTimeDay::$dayMap[$i]));
            $page->getCell($col1.'3')->getStyle()->applyFromArray($this->styleSettings['typeLabel']);
        }

        foreach ($this->days as $day) {
            $dayLabel = $day->getDay();
            $page->setCellValue('A'.$rowStart,$dayLabel);
            $page->getStyle('A' . $rowStart)->applyFromArray($this->styleSettings['datelabel']);
            $page->mergeCells('A'.$rowStart.':A'.($rowStart+$day->getHeight()-1));
            $day->renderDay($rowStart, $page, $this->styleSettings['timelabel'], $this->styleSettings['sumlabel']);
            $rowStart += $day->getHeight();
        }

        $page->setCellValue('A' . ($rowStart), 'Összeg (óra)');




        $logger->Log('log/excel.txt', 'all type: ' . json_encode(WorkTimeDay::$dayMap));
        $sum = [];
        for($i=0;$i<count(WorkTimeDay::$dayMap);$i++){
            $row = WorkTimeMonth::getNameFromNumber(2 + $i * 2);
            $dayType = $this->getType(WorkTimeDay::$dayMap[$i]);
            $page->setCellValue($row.($rowStart),'=SUM('. $row.$startFrom.':'. $row.($rowStart-1).')');
            $sum[$dayType['entryTypeId']]=$row.($rowStart);
//            getCell('B10')->getCalculatedValue()
        }
        $logger->Log('log/excel.txt', 'sum'.json_encode($sum));

        //Rendering overall sum
        $id=0;
        $formulaMap=[];
        for($i=0;$i<count(WorkTimeDay::$dayMap);$i++){
            $dayType = $this->getType(WorkTimeDay::$dayMap[$i]);
            $logger->Log('log/excel.txt', 'parsing daytype: ' . json_encode($dayType));

            $ID = $dayType['entryTypeId'];

            if($dayType['entryFormula']!=null){
                $this->getOrCreateFormula($formulaMap,$ID);
                $logger->Log('log/excel.txt', 'parsing formula: ' . $dayType['entryFormula']);

                if(strpos($dayType['entryFormula'], '<<')>0){
                    $exp = explode('<<',$dayType['entryFormula']);
                    $otherID = str_replace('$','',$exp[0]);
                    $this->getOrCreateFormula($formulaMap,$otherID);
                    $this->addFormula($formulaMap[$otherID],$exp[1], $sum);
                    $logger->Log('log/excel.txt', 'adding to other: ' . $formulaMap[$otherID] . ' -> ' . $ID . '=>' . $otherID);
                }else{
                    $this->addFormula($formulaMap[$ID],$dayType['entryFormula'],$sum);
                    $logger->Log('log/excel.txt', 'adding to self: ' . $formulaMap[$ID]." -> ".$ID);
                    $id++;
                }
            }
        }
        $logger->Log('log/excel.txt','map'.json_encode($formulaMap));
        $i=0;
        foreach($formulaMap as $ID=>$formula){
            if($formula!='='){
                $page->setCellValue($this->getNameFromNumber($i) . ($rowStart + 4), $this->getTypeLabel($ID));
                $page->setCellValue($this->getNameFromNumber($i) . ($rowStart + 5), $formula);
                $i++;
            }
        }
        $page->setCellValue('A' . ($rowStart + 3), 'Összesen');
        $page->getCell('A' . ($rowStart + 3))->getStyle()->applyFromArray($this->styleSettings['black']);
        $page->mergeCells('A' . ($rowStart + 3) . ':' . WorkTimeMonth::getNameFromNumber($i - 1) . ($rowStart + 3));
    }

    /**
     * @param $array
     * @param $key
     */
    private function getOrCreateFormula(& $array, $key){
        if(array_key_exists($key,$array)==false)
            $array[$key]="=";
    }

    /**
     * @param $string
     * @param $expression
     * @param $map
     * @return string
     */
    private function addFormula(& $string, $expression, & $map){
        $logger = new Logger();
        $string.=($string=="="?"(":"+(");
//        $keys = [];
//        preg_match('/(\$(\d+))*/g', $expression, $keys, PREG_OFFSET_CAPTURE);
//        $logger->Log('log/excel.txt', 'keys: ' . json_encode($keys). ' in expression: '.$expression);
        $str = $expression;
        foreach($map as $key=>$row){
            $logger->Log('log/excel.txt', 'AddFormula: ' . $key);
            $str = str_replace('$'.$key,$row, $str);
        }

        $string.=$str.")";
    }

    /**
     * Excel column name by index
     * 0==A, 1==B...., 26=AA
     * @param $num
     * @return string
     */
    public static function getNameFromNumber($num){
        $numeric = $num%26;
        $letter = chr(65+$numeric);
        $num2 = intval($num/26);
        if($num2>0){
            return WorkTimeMonth::getNameFromNumber($num2-1).$letter;
        }else{
            return $letter;
        }
    }

    public function getDateLabel(){
        return $this->date->format('Y-m-d');
    }

}