<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day11Puzzle2Command extends Command
{
    protected static $defaultName = 'day11:puzzle2';

    protected function configure()
    {
        $this
            ->setDescription('Day 11: Puzzle 2')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $lines = explode("\n", file_get_contents(dirname(__DIR__)."../../input/day11.txt"));

        $grid = [];
        foreach ($lines as $line) {
            $grid[] = str_split($line);
        }

        $prev = -1;
        $current = 0;
        while ($prev != $current) {
            $grid = $this->simulate($grid);
            $prev = $current;
            $current = $this->getOccupiedSeats($grid);
        }

        $io->success("The number of occupied seats is ".$this->getOccupiedSeats($grid));

        return Command::SUCCESS;
    }

    private function simulate($grid)
    {
        $newgrid = [];

        for ($i = 0; $i < count($grid); $i++) {
            for ($j = 0; $j < count($grid[$i]); $j++) {
                if ($grid[$i][$j] == 'L' && $this->getSeatStates($grid, $i, $j)['#'] == 0) {
                    $newgrid[$i][$j] = '#';
                } elseif ($grid[$i][$j] == '#' && $this->getSeatStates($grid, $i, $j)['#'] >= 5) {
                    $newgrid[$i][$j] = 'L';
                } else {
                    $newgrid[$i][$j] = $grid[$i][$j];
                }
            }
        }

        return $newgrid;
    }

    private function getSeatStates($grid, $i, $j) : array
    {
        $states = ['.' => 0, 'L' => 0, '#' => 0];

        $states[$this->getVisibleSeat($grid, $i, $j, -1, -1)]++;
        $states[$this->getVisibleSeat($grid, $i, $j, -1, 0)]++;
        $states[$this->getVisibleSeat($grid, $i, $j, -1, 1)]++;
        $states[$this->getVisibleSeat($grid, $i, $j, 0, -1)]++;
        $states[$this->getVisibleSeat($grid, $i, $j, 0, 1)]++;
        $states[$this->getVisibleSeat($grid, $i, $j, 1, -1)]++;
        $states[$this->getVisibleSeat($grid, $i, $j, 1, 0)]++;
        $states[$this->getVisibleSeat($grid, $i, $j, 1, 1)]++;

        return $states;
    }

    private function getVisibleSeat($grid, $i, $j, $x, $y) : string
    {
        $counter = 1;
        while (true) {
            if (!isset($grid[$i + ($counter * $x)][$j + ($counter * $y)])) {
                return '.';
            }
            if ($grid[$i + ($counter * $x)][$j + ($counter * $y)] != '.') {
                return $grid[$i + ($counter * $x)][$j + ($counter * $y)];
            } else {
                $counter++;
            }
        }
    }

    private function getOccupiedSeats($grid)
    {
        $occupied = 0;
        foreach ($grid as $row) {
            foreach ($row as $seat) {
                $occupied += $seat == "#" ? 1 : 0;
            }
        }

        return $occupied;
    }
}
