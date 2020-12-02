<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day2Puzzle1Command extends Command
{
    protected static $defaultName = 'day2:puzzle1';

    protected function configure()
    {
        $this
            ->setDescription('Day 2: Puzzle 1')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $valid = 0;
        $lines = explode("\n", file_get_contents(dirname(__DIR__)."../../input/day2.txt"));
        foreach ($lines as $line) {
            preg_match("/(?P<min>(\d+))-(?P<max>(\d+)) (?P<letter>(\w+)): (?P<password>(\w+))/", $line, $matches);
            $occurences = count_chars($matches['password'], 1);
            if (isset($occurences[ord($matches['letter'])]) && $occurences[ord($matches['letter'])] >= $matches['min'] && $occurences[ord($matches['letter'])] <= $matches['max']) {
                ++$valid;
            }
        }

        $io->success("The number of valid passwords is $valid");

        return Command::SUCCESS;
    }
}
