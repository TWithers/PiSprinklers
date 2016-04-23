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
        $gpio = new Gpio();

        $stop = $this->getContainer()->get('doctrine')->getRepository('SprinklerBundle:Timer')->findBy(['day'=>$day,'end'=>$time]);
        foreach($stop as $timer){
            $zone = $timer->getZone();
            $output->writeln('Stopping Zone #'.$zone->getId().' ('.$zone->getName().')');

            if(!$gpio->isValidPin($zone->getRelay())){
                $output->writeln('GPIO pin '.$zone->getRelay().'is not valid');
                continue;
            }
            if(!$gpio->isExported($zone->getRelay) || $gpio->currentDirection($zone->getRelay())!==Gpio::DIRECTION_OUT){
                $gpio->setup($zone->getRelay(),Gpio::DIRECTION_OUT);
            }
            $gpio->output($zone->getRelay(),Gpio::IO_VALUE_ON); //This is to ensure that 0 turns it on and 1 turns off to avoid issues when the system reboots.

        }

        $start = $this->getContainer()->get('doctrine')->getRepository('SprinklerBundle:Timer')->findBy(['day'=>$day,'start'=>$time]);
        foreach($start as $timer){
            $zone = $timer->getZone();
            $output->writeln('Starting Zone #'.$zone->getId().' ('.$zone->getName().')');
            
            if(!$gpio->isValidPin($zone->getRelay())){
                $output->writeln('GPIO pin '.$zone->getRelay().'is not valid');
                continue;
            }
            if(!$gpio->isExported($zone->getRelay) || $gpio->currentDirection($zone->getRelay())!==Gpio::DIRECTION_OUT){
                $gpio->setup($zone->getRelay(),Gpio::DIRECTION_OUT);
            }
            $gpio->output($zone->getRelay(),Gpio::IO_VALUE_ON); //This is to ensure that 0 turns it on and 1 turns off to avoid issues when the system reboots.
        }
    }

}
