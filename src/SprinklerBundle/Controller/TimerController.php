<?php

namespace SprinklerBundle\Controller;

use SprinklerBundle\Entity\TimerForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/timer")
 */
class TimerController extends Controller
{
    /**
     * @Route("/", name="timer.index")
     */
    public function indexAction()
    {
        $zoneRepo = $this->get('zone.repository');
        $scheduleRepo = $this->get('schedule.repository');
        $days = [[],['image'=>'mon','name'=>'Monday'],['image'=>'tue','name'=>'Tuesday'],['image'=>'wed','name'=>'Wednesday'],['image'=>'thu','name'=>'Thursday'],['image'=>'fri','name'=>'Friday'],['image'=>'sat','name'=>'Saturday'],['image'=>'sun','name'=>'Sunday']];
        return $this->render('SprinklerBundle:Timer:index.html.twig', ['days' => $days,'zones'=>$zoneRepo->getAllZones(),'timers'=>$scheduleRepo->getTimersByZone()]);
    }
    /**
     * @Route("/add/{relay}", name="timer.create")
     */
    public function createAction($relay, Request $request){
        $zoneRepo = $this->get('zone.repository');
        $timerRepo = $this->get('timer.repository');
        if(!$zoneRepo->isValidRelay($relay)){
            return $this->render("SprinklerBundle:Default:error.html.twig",["error"=>
                [
                    "title"=>"Error Adding Timer",
                    "message"=>"There was an error adding the timer to the zone.  The zone you are trying to append a timer to does not exist.",
                    "code"=>"timer.create.$relay",
                ]
            ]);
        }
        $zone = $zoneRepo->loadZoneByRelay($relay);
        $timerForm = new TimerForm();
        $form = $this->createFormBuilder($timerForm)
            ->add('start', TimeType::class, [
                'placeholder' => ['hour' => 'Hour', 'minute' => 'Minute'],
                'with_seconds'=>false,
                'input'=>'string',
                'label'=>'Start time for timer: ',
            ])
            ->add('duration',IntegerType::class,['label'=>"Zone duration in minutes: "])
            ->add('days', ChoiceType::class, [
                'choices'  => [
                    'Monday' => 1,
                    'Tuesday' => 2,
                    'Wednesday' => 3,
                    'Thursday' => 4,
                    'Friday' => 5,
                    'Saturday' => 6,
                    'Sunday' => 7,
                ],
                'label'=>'Which Days? ',
                'expanded'=>true,
                'multiple'=>true,
            ])
            ->add('save', SubmitType::class, array('label' => 'Create Timer'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $timerRepo->addTimerFromForm($timerForm,$zone);
            return $this->redirectToRoute('timer.index');
        }

        return $this->render('SprinklerBundle:Timer:add.html.twig', array(
            'form' => $form->createView(),
            'zone' => $zone,
        ));
    }

    /**
     * @Route("/{id}/delete", name="timer.delete")
     */
    public function deleteAction($id){
        $timerRepo = $this->get('timer.repository');
        if(!$timerRepo->isValidTimer($id)){
            return $this->render("SprinklerBundle:Default:error.html.twig",["error"=>
                [
                    "title"=>"Error Deleting Timer",
                    "message"=>"There was an error deleting the timer from the zone.  The timer may have already been deleted.",
                    "code"=>"timer.delete.$id",
                ]
            ]);
        }
        $timerRepo->deleteTimer($id);
        return $this->redirectToRoute('timer.index');
    }
}
