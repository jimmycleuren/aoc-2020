<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day2Puzzle2Command extends Command
{
    protected static $defaultName = 'day2:puzzle2';

    protected function configure()
    {
        $this
            ->setDescription('Day 2: Puzzle 2')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $valid = 0;
        $lines = explode("\n", file_get_contents(dirname(__DIR__)."../../input/day2.txt"));
        foreach ($lines as $line) {
            preg_match("/(?P<first>(\d+))-(?P<second>(\d+)) (?P<letter>(\w+)): (?P<password>(\w+))/", $line, $matches);

            if ($matches['password'][$matches['first'] - 1] == $matches['letter'] && $matches['password'][$matches['second'] - 1] != $matches['letter']) {
                ++$valid;
            }
            if ($matches['password'][$matches['first'] - 1] != $matches['letter'] && $matches['password'][$matches['second'] - 1] == $matches['letter']) {
                ++$valid;
            }
        }

        $io->success("The number of valid passwords is $valid");

        return Command::SUCCESS;
    }
}
