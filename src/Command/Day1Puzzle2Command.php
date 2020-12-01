<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day1Puzzle2Command extends Command
{
    protected static $defaultName = 'day1:puzzle2';

    protected function configure()
    {
        $this
            ->setDescription('Day 1: Puzzle 2')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $numbers = explode("\n", file_get_contents(dirname(__DIR__)."../../input/day1.txt"));
        foreach ($numbers as $i => $x) {
            foreach ($numbers as $j => $y) {
                foreach ($numbers as $k => $z) {
                    if ($x + $y + $z == 2020 && $i < $j && $j < $k) {
                        $io->success("The numbers are $x, $y and $z, the product is ".($x*$y*$z));
                    }
                }
            }
        }

        return Command::SUCCESS;
    }
}
