<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day12Puzzle2Command extends Command
{
    protected static $defaultName = 'day12:puzzle2';

    protected function configure()
    {
        $this
            ->setDescription('Day 12: Puzzle 2')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $lines = explode("\n", file_get_contents(dirname(__DIR__)."../../input/day12.txt"));

        $shipNorth = 0;
        $shipEast = 0;
        $waypointNorth = 1;
        $waypointEast = 10;

        foreach ($lines as $line) {
            $command = substr($line, 0, 1);
            $value = substr($line, 1);

            switch ($command) {
                case "R":
                    $oldVal = $value;
                    if ($oldVal == 90) {
                        $value = 270;
                    }
                    if ($oldVal == 270) {
                        $value = 90;
                    }
                case "L":
                    switch ($value) {
                        case 90:
                            $temp = $waypointEast;
                            $waypointEast = -$waypointNorth;
                            $waypointNorth = $temp;
                            break;
                        case 180:
                            $waypointEast = -$waypointEast;
                            $waypointNorth = -$waypointNorth;
                            break;
                        case 270:
                            $temp = $waypointEast;
                            $waypointEast = $waypointNorth;
                            $waypointNorth = -$temp;
                            break;
                    }
                    break;
                case 'F':
                    $shipNorth += $waypointNorth * $value;
                    $shipEast += $waypointEast * $value;
                    break;
                default:
                    list($waypointNorth, $waypointEast) = $this->move($command, $value, $waypointNorth, $waypointEast);
                    break;
            }
        }

        $io->success("North is $shipNorth, east is $shipEast, the manhattan distance is ".(abs($shipNorth) + abs($shipEast)));

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
