<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day5Puzzle1Command extends Command
{
    protected static $defaultName = 'day5:puzzle1';

    protected function configure()
    {
        $this
            ->setDescription('Day 5: Puzzle 1')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $passes = explode("\n", file_get_contents(dirname(__DIR__)."../../input/day5.txt"));

        $max = 0;
        foreach ($passes as $pass) {
            $seatId = $this->getSeatId($pass);
            $max = max($max, $seatId);
        }

        $io->success("The highest seat id is $max");

        return Command::SUCCESS;
    }

    private function getSeatId($pass)
    {
        $row = [0, 127];
        $col = [0, 7];

        for ($i = 0; $i < 7; $i++) {
            if ($pass[$i] == 'F') {
                $row[1] -= ($row[1] - $row[0] + 1) / 2;
            }
            if ($pass[$i] == 'B') {
                $row[0] += ($row[1] - $row[0] + 1) / 2;
            }
        }

        for ($i = 7; $i < 10; $i++) {
            if ($pass[$i] == 'L') {
                $col[1] -= ($col[1] - $col[0] + 1) / 2;
            }
            if ($pass[$i] == 'R') {
                $col[0] += ($col[1] - $col[0] + 1) / 2;
            }
        }

        return $row[0] * 8 + $col[0];
    }
}
