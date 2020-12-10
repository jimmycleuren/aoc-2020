<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day10Puzzle1Command extends Command
{
    protected static $defaultName = 'day10:puzzle1';

    protected function configure()
    {
        $this
            ->setDescription('Day 10: Puzzle 1')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $contents = file_get_contents(dirname(__DIR__)."../../input/day10.txt");
        $numbers = explode("\n", $contents);
        $numbers = array_merge([0], $numbers, [max($numbers) + 3]);

        sort($numbers);

        $diffs = [1 => 0, 2 => 0, 3 => 0];
        for ($i = 1; $i < count($numbers); $i++) {
            $diffs[$numbers[$i] - $numbers[$i - 1]]++;
        }

        $io->success("The result is ".($diffs[1] * $diffs[3]));

        return Command::SUCCESS;
    }
}
