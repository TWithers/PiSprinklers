<?php

namespace SprinklerBundle\Command;

use PhpGpio\Gpio;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SprinklerCronCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sprinkler:cron')
            ->setDescription('Cron Job run every minute to check if sprinklers should be turned on or off');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        date_default_timezone_set($this->getContainer()->getParameter('timezone'));
        $day = date('N');
        $time = date('H:i');

//        $em = $this->getContainer()->get('doctrine')->getManager();
//        $zoneRepository = $em->getRepository('SprinklerBundle:Zone');
//        $query = $zoneRepository->createQueryBuilder('z')
//            ->where('z.override = TRUE AND z.overrideendtime IS NOT NULL')
//            ->getQuery();
//        $zones = $query->getResult();
//        if(count($zones)>0){
//            foreach($zones as $zone){
//                $output->writeln('Stopping Override on Zone #'.$zone->getId().' ('.$zone->getName().')');
//                $zone->setOverrideendtime(null);
//                $zone->setOverride(false);
//                $em->flush();
//                //handle stop
//            }
//        }

        $gpio = new Gpio();
        $stop = $this->getContainer()->get('doctrine')->getRepository('SprinklerBundle:Timer')->findBy(['day'=>$day,'end'=>$time]);
        foreach($stop as $timer){
            $zone = $timer->getZone();

            $output->writeln('Stopping Zone #'.$zone->getId().' ('.$zone->getName().')');
            if($gpio->currentDirection($zone->getRelay())!=="out") {
                $gpio->setup($zone->getRelay(), "out");
            }
            $gpio->output($zone->getRelay(),1);

        }
        $start = $this->getContainer()->get('doctrine')->getRepository('SprinklerBundle:Timer')->findBy(['day'=>$day,'start'=>$time]);
        foreach($start as $timer){
            $zone = $timer->getZone();
            $output->writeln('Starting Zone #'.$zone->getId().' ('.$zone->getName().')');
            if($gpio->currentDirection($zone->getRelay())!=="out") {
                $gpio->setup($zone->getRelay(), "out");
            }
            $gpio->output($zone->getRelay(),0);
        }
    }

}
