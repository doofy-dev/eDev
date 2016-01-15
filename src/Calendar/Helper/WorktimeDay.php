<?php
/**
 * User: teeeb
 * Date: 2016. 01. 12.
 * Time: 14:56
 */

namespace Calendar\Helper;


/**
 * Class WorkTimeDay
 * @package Calendar\Helper
 */
class WorkTimeDay
{
   /**
     * @var array list of data per day
     */
    private $dataTypes = array(
//        '1' => array('WorkTimeDayType1', 'WorkTimeDayType2')
//        '2'=> array('WorkTimeDayType1', 'WorkTimeDayType2')
    );

    public static $dayMap = array();

    /**
     * @var int height of the current day
     * used when more than one same day type is available at the day
     */
    private $height = 1;
    /**
     * @var string day label
     */
    private $day;


    public function __construct(\DateTime  $day)
    {
        foreach(WorkTimeDay::$dayMap as $types)
            $this->dataTypes[$types] = array();
        $this->day = $day->format('Y-m-d');
    }


    /**
     * @param $id
     * @param WorkTimeDayType $type
     */
    public function addType($id, WorkTimeDayType $type){
        if(array_key_exists($id,$this->dataTypes)==false){
            $this->dataTypes[$id]=[];
            WorkTimeDay::$dayMap[] = $id;
        }
        $this->dataTypes[$id][]=$type;


        if(count($this->dataTypes[$id])>$this->height){
            $this->height = count($this->dataTypes[$id]);
        }
    }

    public function renderDay($startRow, \PHPExcel_Worksheet $page, array $timeStyle, array $sumStyle){
        $start = 1;
        foreach ($this->dataTypes as $key=>$dataTypeList) {
            $rowIndex = $startRow;
            $col1 = WorkTimeMonth::getNameFromNumber($start);
            $col2 = WorkTimeMonth::getNameFromNumber($start+1);

            foreach($dataTypeList as $dataType){
                $page->setCellValue($col1 . $rowIndex, $dataType->getTimeCell());
                $page->getCell($col1.$rowIndex)->getStyle()->applyFromArray($timeStyle);
                $page->setCellValue($col2 . $rowIndex, $dataType->getSum());
                $page->getCell($col2.$rowIndex)->getStyle()->applyFromArray($sumStyle);
                $page->mergeCells($col1.($rowIndex+1).':'.$col2.($rowIndex+1));
                $page->setCellValue($col1.($rowIndex+1),$dataType->getComment());
                $rowIndex+=2;
            }
            $start+=2;
        }
    }

    public function getDay(){
        return $this->day;
    }

    public function getHeight(){
        return $this->height*2;
    }
}