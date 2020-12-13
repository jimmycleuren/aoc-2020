<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day13Puzzle1Command extends Command
{
    protected static $defaultName = 'day13:puzzle1';

    protected function configure()
    {
        $this
            ->setDescription('Day 13: Puzzle 1')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        list($timestamp, $busses) = explode("\n", file_get_contents(dirname(__DIR__)."../../input/day13.txt"));

        $busses = array_filter(explode(",", $busses), function($value) {return $value != "x";});

        $min = $timestamp;
        $result = null;
        foreach ($busses as $bus) {
            $cycles = ceil($timestamp / $bus);
            $wait = ($cycles * $bus) - $timestamp;
            if ($wait < $min) {
                $min = $wait;
                $result = $bus;
            }
        }

        $io->success("You have to take bus $result and wait $min minutes for it. The result is ".$result * $min);

        return Command::SUCCESS;
    }
}
