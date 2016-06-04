<?php
namespace SprinklerBundle\Repository;

use SprinklerBundle\Entity\Zone;
use SprinklerBundle\Entity\Timer;

class ScheduleRepository extends Repository{
    public function getWeekSchedule(){
        $timers = $this->em->getRepository('SprinklerBundle:Timer')->findBy([],['day' => 'ASC', 'start' => 'ASC','zone'=>'ASC',]);
        $schedule=[
            [
                'name'=>'Monday',
                'image'=>'mon',
                'timers'=>[],
            ],
            [
                'name'=>'Tuesday',
                'image'=>'tue',
                'timers'=>[],
            ],
            [
                'name'=>'Wednesday',
                'image'=>'wed',
                'timers'=>[],
            ],
            [
                'name'=>'Thursday',
                'image'=>'thu',
                'timers'=>[],
            ],
            [
                'name'=>'Friday',
                'image'=>'fri',
                'timers'=>[],
            ],
            [
                'name'=>'Saturday',
                'image'=>'sat',
                'timers'=>[],
            ],
            [
                'name'=>'Sunday',
                'image'=>'sun',
                'timers'=>[],
            ],
        ];
        foreach($timers as $timer){
            $schedule[$timer->getDay()-1]['timers'][]=[
                'zone'=>$timer->getZone()->getName(),
                'start'=>date("h:i",strtotime($timer->getStart())),
                'end'=>date("h:i A",strtotime($timer->getEnd())),
            ];
        }
        return $schedule;
    }

    public function getNextUp(){
        $timers = $this->em->getRepository('SprinklerBundle:Timer')->findBy([],['day' => 'ASC', 'start' => 'ASC','zone'=>'ASC',]);
        if(!isset($timers[0])){
            return[
                "name"=>"No timers exist yet!",
                "start"=>"12:00A",
                "end"=>"12:00A",
                "image"=>1,
            ];
        }
        $now=null;
        $next=null;
        foreach($timers as $timer){
            if($timer->getDay()==date('N')){
                if(strtotime("now")>=strtotime("today ".$timer->getStart()) && strtotime("now")<=strtotime("today ".$timer->getEnd())) {
                    $now = $timer;
                    break;
                }
                if (strtotime("now")<strtotime("today ".$timer->getStart())){
                    $next = $timer;
                    break;
                }
            }
            if($timer->getDay()>date('N')){
                $next=$timer;
                break;
            }
        }
        if($now===null && $next===null){
            $next = $timers[0];
        }

        if($now!==null){
            $next = $now;
            $title = $next->getZone()->getName() .' - Running';
        }else{
            $title = $next->getZone()->getName(). ' - Up Next';
        }
        if($next->getDay()==date('N')){
            $title.=' (Today)';
        }else{
            $dayOfWeek = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
            $title.=' ('.$dayOfWeek[$next->getDay()].')';
        }
        $timer = [
            'name'=> $title,
            'start'=>date("h:ia",strtotime($next->getStart())),
            'end'=>date("h:ia",strtotime($next->getEnd())),
            'image'=>$next->getZone()->getImage(),
        ];

        return $timer;
    }

    public function getTimersByZone(){
        $timers = $this->em->getRepository('SprinklerBundle:Timer')->findBy([],['day' => 'ASC', 'start' => 'ASC','zone'=>'ASC',]);
        $ret=[];
        foreach($timers as $timer){
            if(!isset($ret[$timer->getZone()->getId()])){
                $ret[$timer->getZone()->getId()]=[[],[],[],[],[],[],[],[]];
            }
            $ret[$timer->getZone()->getId()][$timer->getDay()][]=$timer;
        }
        return $ret;
    }
}