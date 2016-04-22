<?php

namespace SprinklerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class SprinklerWeekCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sprinkler:week')
            ->setDescription('View a simple table with the settings for the different zones')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $zones = $this->getContainer()->get('doctrine')->getRepository('SprinklerBundle:Zone')->findAll();
        foreach($zones as $zone){
            $output->writeln('-- Zone '.$zone->getId().': '.$zone->getName().' --');
            $timers = $this->getContainer()->get('doctrine')->getRepository('SprinklerBundle:Timer')->findBy(['zoneId'=>$zone->getId()],['day'=>'ASC','start'=>'ASC']);
            $table = new Table($output);
            $table->setHeaders(array('Day', 'Start', 'End','Run Time'));
            $rows=[];
            $r=-1;
            foreach($timers as $timer){
                $r++;
                $dowMap = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday','Sunday');
                $start = \DateTime::createFromFormat('H:i', $timer->getStart());
                $end = \DateTime::createFromFormat('H:i', $timer->getEnd());
                if($r>0 && $rows[$r-1][0]==$dowMap[$timer->getDay()]){
                    $day='';
                }else{
                    $day=$dowMap[$timer->getDay()];
                }

                $rows[]=[$day,$start->format('g:i A'),$end->format('g:i A'),$end->diff($start)->format('%i minutes')];
            }
            $table->setRows($rows);
            $table->render();
            $output->writeln(' ');
        }
    }

}
