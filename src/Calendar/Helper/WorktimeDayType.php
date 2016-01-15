<?php
/**
 * User: teeeb
 * Date: 2016. 01. 12.
 * Time: 14:56
 */

namespace Calendar\Helper;


use Application\Entity\Calendar;

class WorkTimeDayType
{
    private $start, $end, $sum, $comment;
    public function __construct(Calendar $day)
    {
        $this->start = ($day->getStartTime()!=null?$day->getStartTime()->format('H:i'):'');
        $this->end = ($day->getEndTime()!=null?$day->getEndTime()->format('H:i'):'');

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