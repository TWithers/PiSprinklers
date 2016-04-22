<?php
namespace SprinklerBundle\Service;

use SprinklerBundle\SprinklerBundle;

class WeatherService{

    public $temp;
    public $city;
    public $state;
    public $icon;

    public function __construct($repo){
        if($repo->forecast === null){
            $this->temp = '?';
            $this->city = 'SomeTown';
            $this->state = 'USA';
            $this->icon = 'partly-cloudy-day';
            return;
        }
        $this->temp = round($repo->forecast->getCurrently()->getTemperature()->getCurrent());
        $this->city = $repo->location->getLocality();
        $this->state = $repo->location->getDistrict();
        $this->icon = $repo->forecast->getCurrently()->getIcon();
    }
}