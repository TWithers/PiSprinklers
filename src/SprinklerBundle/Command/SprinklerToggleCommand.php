<?php

namespace SprinklerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SprinklerToggleCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sprinkler:toggle')
            ->setDescription('Toggle a zone on and off.')
            ->addArgument('zone', InputArgument::REQUIRED, 'The zone id or name to toggle')
            ->addOption('mode', null, InputOption::VALUE_REQUIRED, 'on/off/toggle for the zone','toggle')
            ->addOption('timer', null, InputOption::VALUE_REQUIRED, 'Minutes to set the timer for if turning on');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('argument');
        $em = $this->getContainer()->get('doctrine')->getManager();
        if(is_numeric($id)){
            $zone = $em->getRepository('SprinklerBundle:Zone')->findOneById($id);
        }else{
            $zone = $em->getReposittory('SprinklerBundle:Zone')->findOneByName($id);
        }
        if($zone===null){
            $output->writeln('Zone does not exist.');
            return;
        }

        //if toggle -- zone is off, turn it on, set override to true
        //if toggle -- zone is on, turn it off, set override to false

        //if on -- zone is off, turn it on, set override to true
        //if on -- zone is on, output error message

        //if off -- zone in on, turn it off, set override to false
        //if off -- zone is off, output error message
        
        switch($input->getOption('mode')){
            case 'toggle':default:
                if($zone->getOverride()===true){
                    $zone->setOverride(false);
                }else{
                    $zone->setOverride(true);
                }
            case 'on':
                $zone->setOverride(true);
                break;
            case 'off':
                $zone->setOverride(false);
        }

        //if timer is specified, set end time
        //check if end time is after next start time
        //if end time is after next start time, output error message
        if($input->getOption('timer') && $zone->getOverride()===true){
            $date = new DateTime('now');
            $date->add(new DateInterval('P'.$input->getOption('timer').'D'));
            $zone->setOverrideendtime($date->format('H:i'));
        }

        $output->writeln('Command result.');
    }

}
