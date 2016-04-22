<?php
namespace SprinklerBundle\Repository;


use SprinklerBundle\Entity\Timer;
use SprinklerBundle\Entity\TimerForm;
use SprinklerBundle\Entity\Zone;

class TimerRepository extends Repository
{
    public $lastTimer = null;
    public function addTimerFromForm(TimerForm $form,Zone $zone){
        foreach($form->getDays() as $day){
            $timer = new Timer;
            $timer->setStart(date("H:i",strtotime($form->getStart())));
            $timer->setEnd(date("H:i",strtotime($form->getStart())+($form->getDuration()*60)));
            $timer->setZone($zone);
            $timer->setDay($day);
            $this->em->persist($timer);
        }
        $this->em->flush();
    }
    public function isValidTimer($id){
        $this->loadTimerById($id);
        return $this->lastTimer!==null;
    }
    public function deleteTimer($id){
        if($this->lastTimer===null || $this->lastTimer->getId()!=$id){
            $timer = $this->loadTimerById($id);
            if($timer===null){
                return false;
            }
        }else{
            $timer = $this->lastTimer;
        }
        $this->em->remove($timer);
        $this->em->flush();
        return true;
    }
    public function loadTimerById($id){
        $this->lastTimer = $this->em->getRepository('SprinklerBundle:Timer')->find($id);
        return $this->lastTimer;
    }
}