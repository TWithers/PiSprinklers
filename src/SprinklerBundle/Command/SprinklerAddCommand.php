<?php

namespace SprinklerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SprinklerAddCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sprinkler:add')
            ->setDescription('...')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title("Test");
        $io->section('Adding a User');

// ...

$io->section('Generating the Password');
// use simple strings for short notes
$io->note('Lorem ipsum dolor sit amet');

// ...

// consider using arrays when displaying long notes
$io->note(array(
    'Lorem ipsum dolor sit amet',
    'Consectetur adipiscing elit',
    'Aenean sit amet arcu vitae sem faucibus porta',
));
$io->ask('Number of workers to start', 1, function ($number) {
    if (!is_numeric($number)) {
        throw new \RuntimeException('You must type an integer.');
    }

    return $number;
});
    }

}
