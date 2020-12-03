<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day3Puzzle1Command extends Command
{
    protected static $defaultName = 'day3:puzzle1';

    protected function configure()
    {
        $this
            ->setDescription('Day 3: Puzzle 1')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $grid = [];
        $lines = explode("\n", file_get_contents(dirname(__DIR__)."../../input/day3.txt"));

        $height = count($lines);
        $width = $height * 3;

        foreach ($lines as $row => $line) {
            $col = 0;
            $grid[$row] = [];
            while ($col < $width) {
                $grid[$row][$col] = $line[$col % strlen($line)];
                ++$col;
            }
        }

        $row = 0;
        $col = 0;
        $trees = 0;

        while ($row < $height) {
            $row += 1;
            $col += 3;
            if ($row < $height && $grid[$row][$col] == '#') {
                ++$trees;
            }
        }

        $io->success("The number of trees is $trees");

        return Command::SUCCESS;
    }
}
