<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day6Puzzle2Command extends Command
{
    protected static $defaultName = 'day6:puzzle2';

    protected function configure()
    {
        $this
            ->setDescription('Day 6: Puzzle 2')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $contents = file_get_contents(dirname(__DIR__)."../../input/day6.txt")." ";
        preg_match_all("/((\w+)\s)+/", $contents, $matches);

        $total = 0;
        foreach ($matches[0] as $group) {
            $persons = array_filter(explode("\n", $group));
            $intersect = str_split($persons[0]);
            for ($i = 1; $i < count($persons); $i++) {
                $intersect = array_intersect($intersect, str_split($persons[$i]));
            }

            $total += count($intersect);
        }

        $io->success("The total is $total");

        return Command::SUCCESS;
    }
}
