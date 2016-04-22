<?php
namespace SprinklerBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class TimerForm{

    /**
     * @Assert\NotBlank
     */
    private $start;

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="numeric",message="This value should be a number of minutes to run the zone.")
     */
    private $duration;

    /**
     * @Assert\Count(min=1,minMessage="Please select at least 1 day for the timer.")
     */
    private $days;
    


    public function setStart($val){
        $this->start=$val;
    }

    public function getStart(){
        return $this->start;
    }


    public function setDuration($val){
        $this->duration=$val;
    }

    public function getDuration(){
        return $this->duration;
    }


    public function setDays($val){
        $this->days=$val;
    }
    
    public function getDays(){
        return $this->days;
    }
}