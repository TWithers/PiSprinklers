<?php
namespace SprinklerBundle\Repository;

class WeatherRepository{

    public $overcast;
    public $forecast;
    public $location;

    public function __construct($api,$geocode,$address){
        $this->location = $geocode->get($address);
        $this->overcast = $api;
        $this->forecast = $api->getForecast($this->location->getLatitude(),$this->location->getLongitude());
    }

    public function getCurrentWeather(){
        return [
            'temp'=>round($this->forecast->getCurrently()->getTemperature()->getCurrent()),
            'city'=>$this->location->getLocality(),
            'state'=>$this->location->getDistrict(),
            'icon'=>$this->forecast->getCurrently()->getIcon(),
        ];
    }
    
}