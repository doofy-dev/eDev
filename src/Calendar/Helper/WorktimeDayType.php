<?php
/**
 * User: teeeb
 * Date: 2016. 01. 12.
 * Time: 14:56
 */

namespace Calendar\Helper;


use Application\Entity\Calendar;
use decoy\log\Logger;

class WorkTimeDayType
{
    private $start, $end, $sum, $comment, $poject, $task;
    public function __construct(Calendar $day)
    {
        $this->start = ($day->getStartTime()!=null?$day->getStartTime()->format('H:i'):'');
        $this->end = ($day->getEndTime()!=null?$day->getEndTime()->format('H:i'):'');
        $logger = new Logger();
        $this->poject = ($day->getProject()==null?null:$day->getProject()->getProjectName());
        $this->task = ($day->getTask()==null?null:$day->getTask()->getTaskName());
        $logger->Log('log/excel-project.txt', '------------');
        $logger->Log('log/excel-project.txt', $this->poject);
        $logger->Log('log/excel-project.txt', $this->task);

        if($day->getStartTime()!=null && $day->getEndTime()!=null) {
            $startH = intval($day->getStartTime()->format('H'));
            $startM = intval($day->getStartTime()->format('i'))/60.0;


            $endH = intval($day->getEndTime()->format('H'));
            $endM = intval($day->getEndTime()->format('i')) / 60.0;

            if($endH==0)
                $endH=24;

            $this->sum =  ($endH+$endM)-($startH+$startM);
        }
        else
            $this->sum=0;
        $this->comment = $day->getComment();
        if($this->comment==null)
            $this->comment='';
    }

    /**
     * @return string
     */
    public function getPoject()
    {
        return $this->poject;
    }

    /**
     * @return string
     */
    public function getTask()
    {
        return $this->task;
    }

    public function getTimeCell(){
        return  $this->start." - ".$this->end;
    }

    public function getSum(){
        return $this->sum;
    }

    public function getComment(){
        return $this->comment;
    }

}