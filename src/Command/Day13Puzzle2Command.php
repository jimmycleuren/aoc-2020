<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day13Puzzle2Command extends Command
{
    protected static $defaultName = 'day13:puzzle2';

    protected function configure()
    {
        $this
            ->setDescription('Day 13: Puzzle 2')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        list($timestamp, $busses) = explode("\n", file_get_contents(dirname(__DIR__)."../../input/day13.txt"));

        $busses = explode(",", $busses);

        $schedule = [];
        foreach ($busses as $key => $bus) {
            if ($bus != 'x') {
                $schedule[$bus] = $key;
            }
        }

        $correct = [1];

        $max = max(array_filter($busses, function($value) {return $value != "x";}));

        $t = 100000000000000;
        while (($t + $schedule[$max]) % $max != 0) {
            $t++;
        }

        while (true) {
            $ok = true;
            foreach ($schedule as $bus => $offset) {
                if (($t + $offset) % $bus != 0) {
                    $ok = false;
                    break;
                } else {
                    if (!in_array($bus, $correct)) {
                        $correct[] = $bus;
                    }
                }
            }
            if ($ok) {
                break;
            }
            $t += $this->getDenominator($correct);
            
        }

        $io->success("The timestamp t is $t");

        return Command::SUCCESS;
    }

    private function getDenominator($values)
    {
        $result = 1;
        foreach ($values as $value) {
            $result *= $value;
        }

        return $result;
    }
}
