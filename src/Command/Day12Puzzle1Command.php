<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day12Puzzle1Command extends Command
{
    protected static $defaultName = 'day12:puzzle1';

    protected function configure()
    {
        $this
            ->setDescription('Day 12: Puzzle 1')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $lines = explode("\n", file_get_contents(dirname(__DIR__)."../../input/day12.txt"));

        $north = 0;
        $east = 0;
        $direction = 'E';
        $directions = ['N', 'E', 'S', 'W'];
        foreach ($lines as $line) {
            $command = substr($line, 0, 1);
            $value = substr($line, 1);

            switch ($command) {
                case "L":
                    $key = array_search($direction, $directions);
                    $direction = $directions[(($key - $value / 90) + 4) % 4];
                    break;
                case "R":
                    $key = array_search($direction, $directions);
                    $direction = $directions[(($key + $value / 90) + 4) % 4];
                    break;
                case 'F':
                    list($north, $east) = $this->move($direction, $value, $north, $east);
                    break;
                default:
                    list($north, $east) = $this->move($command, $value, $north, $east);
                    break;
            }
        }

        $io->success("North is $north, east is $east, the manhattan distance is ".(abs($north) + abs($east)));

        return Command::SUCCESS;
    }

    private function move($dir, $value, $north, $east)
    {
        switch ($dir) {
            case "N":
                $north += $value;
                break;
            case "E":
                $east += $value;
                break;
            case "S":
                $north -= $value;
                break;
            case "W":
                $east -= $value;
                break;
        }
        return [$north, $east];
    }
}
