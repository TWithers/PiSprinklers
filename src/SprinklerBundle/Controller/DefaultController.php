<?php

namespace SprinklerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $scheduleRepo = $this->get('schedule.repository');
        $weatherRepo = $this->get('weather.repository');
        
        return $this->render('SprinklerBundle:Default:index.html.twig',[
            'timers'=>$scheduleRepo->getWeekSchedule(),
            'upnext'=>$scheduleRepo->getNextUp(),
//            'weather'=>$weatherRepo->getCurrentWeather(),
        ]);
    }

    /**
     * @Route("/forecast",name="forecast.redirect")
     */
    public function forecastAction(){
        $weatherRepo = $this->get('weather.repository');
        $latitude = $weatherRepo->location->getLatitude();
        $longitude = $weatherRepo->location->getLongitude();

        return $this->redirect("http://forecast.io/#/f/$latitude,$longitude");
    }
}
