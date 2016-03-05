<?php

namespace SprinklerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Helper\Table;

class SprinklerWeatherCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sprinkler:weather')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $overcast = $this->getContainer()->get('overcast');
        $forecast = $overcast->getForecast(33.326030,-111.874745);
        $output->writeln($overcast->getApiCalls().' API Calls Today');
        $table = new Table($output);
        $table->setRows([
            ['Current Temp',$forecast->getCurrently()->getTemperature()->getCurrent()],
            ['Feels Like',$forecast->getCurrently()->getApparentTemperature()->getCurrent()],
            ['Low',$forecast->getCurrently()->getTemperature()->getMin()],
            ['High',$forecast->getCurrently()->getTemperature()->getMax()],
        ]);
        $table->render();
        $output->writeln(' ');
        $output->writeln( 'Daily Summary: '.$forecast->getDaily()->getSummary() );
        foreach($forecast->getDaily()->getData() as $dailyData) {
            $table = new Table($output);
            $table->setRows([
                ['Date',$dailyData->getTime()->format('D, M jS y')],
                ['Min Temp',$dailyData->getTemperature()->getMin()],
                ['Max Temp',$dailyData->getTemperature()->getMax()],
                ['Precipitation Probability',$dailyData->getPrecipitation()->getProbability()],
                ['Precipitation Intensity',$dailyData->getPrecipitation()->getIntensity()],
                ['Wind Speed',$dailyData->getWindSpeed()],
                ['Wind Direction',$dailyData->getWindBearing()],
                ['Visibility',$dailyData->getVisibility()],
                ['Cloud Coverage',$dailyData->getCloudCover()],
            ]);
            $table->render();
            $output->writeln(' ');
        }
    }

}
