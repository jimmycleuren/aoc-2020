<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day3Puzzle2Command extends Command
{
    protected static $defaultName = 'day3:puzzle2';

    protected function configure()
    {
        $this
            ->setDescription('Day 3: Puzzle 2')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $grid = [];
        $lines = explode("\n", file_get_contents(dirname(__DIR__)."../../input/day3.txt"));

        $height = count($lines);
        $width = $height * 7;

        foreach ($lines as $row => $line) {
            $col = 0;
            $grid[$row] = [];
            while ($col < $width) {
                $grid[$row][$col] = $line[$col % strlen($line)];
                ++$col;
            }
        }

        $result = 1;

        foreach ([[1,1], [1,3], [1,5], [1,7], [2,1]] as list($x, $y)) {
            $trees = 0;
            $row = 0;
            $col = 0;
            while ($row < $height) {
                $row += $x;
                $col += $y;
                if ($row < $height && $grid[$row][$col] == '#') {
                    ++$trees;
                }
            }
            $result *= $trees;
        }

        $io->success("The product of all attempts is $result");

        return Command::SUCCESS;
    }
}
