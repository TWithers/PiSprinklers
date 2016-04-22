<?php

namespace SprinklerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use SprinklerBundle\Entity\Zone;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/zone")
 */
class ZoneController extends Controller
{
    /**
     * @Route("/", name="zone.index")
     */
    public function indexAction()
    {
        $zoneRepo = $this->get('zone.repository');
        return $this->render('SprinklerBundle:Zone:index.html.twig',[
            'zones'=>$zoneRepo->getAllZones()
        ]);
    }

    /**
     * @Route("/add", name="zone.add")
     */
    public function addAction(Request $request){
        $zone = new Zone();
        $zoneRepo = $this->get('zone.repository');
        $form = $this->createFormBuilder($zone)
            ->add('name', TextType::class,['label'=>'Zone name (eg "Front Sprinklers"): '])
            ->add('relay', TextType::class,['label'=>"GPIO Pin Connection: "])
            ->add('image', ChoiceType::class, [
                'choices'  => [
                    'Sprinklers' => 1,
                    'Drip Lines' => 2,
                ],
                'label'=>'Zone Type: ',
            ])
            ->add('save', SubmitType::class, array('label' => 'Create Zone'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $zoneRepo->addZone($zone);
            return $this->redirectToRoute('zone.index');
        }

        return $this->render('SprinklerBundle:Zone:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{relay}/delete", name="zone.delete")
     */
    public function deleteAction($relay, Request $request){
        $zoneRepo = $this->get('zone.repository');

        if($zoneRepo->isValidRelay($relay)){
            $zoneRepo->deleteZone($relay);
            return $this->redirectToRoute('zone.index');
        }else{
            return $this->render("SprinklerBundle:Default:error.html.twig",["error"=>
                [
                    "title"=>"Error Deleting Zone",
                    "message"=>"There was an error deleting the zone.  The zone you are trying to delete does not exist, and may have been deleted already.",
                    "code"=>"zone.delete.$relay",
                ]
            ]);
        }
    }

    /**
     * @Route("/{relay}/edit", name="zone.edit")
     */
    public function editAction($relay, Request $request){
        $zoneRepo = $this->get('zone.repository');
        $zone = $zoneRepo->loadZoneByRelay($relay);
        if($zone===null){
            return $this->render("SprinklerBundle:Default:error.html.twig",["error"=>
                [
                    "title"=>"Error With Zone",
                    "message"=>"There was an error loading the zone information.  The zone may not exist in the system or may have been deleted.",
                    "code"=>"zone.edit.$relay",
                ]
            ]);
        }
        $form = $this->createFormBuilder($zone)
            ->add('name', TextType::class,['label'=>'Zone name (eg "Front Sprinklers"): '])
            ->add('relay', TextType::class,['label'=>"GPIO Pin Connection: "])
            ->add('image', ChoiceType::class, [
                'choices'  => [
                    'Sprinklers' => 1,
                    'Drip Lines' => 2,
                ],
                'label'=>'Zone Type: ',
            ])
            ->add('save', SubmitType::class, array('label' => 'Update Zone'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $zoneRepo->updateZone($zone);
            return $this->redirectToRoute('zone.index');
        }

        return $this->render('SprinklerBundle:Zone:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
