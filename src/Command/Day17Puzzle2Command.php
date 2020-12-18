<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day17Puzzle2Command extends Command
{
    protected static $defaultName = 'day17:puzzle2';

    public function __construct(LoggerInterface $logger, string $name = null)
    {
        $this->logger = $logger;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Day 17: Puzzle 2')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        ini_set('memory_limit', "2048M");

        $lines = explode("\n", file_get_contents(dirname(__DIR__)."../../input/day17.txt"));

        $grid = [];

        $size = 12;

        for ($i = -$size; $i < $size; $i++) {
            for ($j = -$size; $j < $size; $j++) {
                for ($k = -$size; $k < $size; $k++) {
                    for ($l = -$size; $l < $size; $l++) {
                        $grid[$i][$j][$k][$l] = false;
                    }
                }
            }
        }

        foreach ($lines as $i => $line) {
            foreach (str_split($line) as $j => $char) {
                $grid[$i][$j][0][0] = ($char == '#');
            }
        }

        $this->logger->info("Start: ".$this->countActive($grid));
        for ($cycle = 0; $cycle < 6; $cycle++) {
            $grid = $this->simulate($grid);
            $this->logger->info("Simulation #$cycle: ".$this->countActive($grid));
        }

        $io->success("The number of active cubes is ".$this->countActive($grid));

        return Command::SUCCESS;
    }

    private function simulate($grid)
    {
        $newGrid = [];

        for ($i = min(array_keys($grid)); $i <= max(array_keys($grid)); $i++) {
            for ($j = min(array_keys($grid[$i])); $j <= max(array_keys($grid[$i])); $j++) {
                for ($k = min(array_keys($grid[$i][$j])); $k <= max(array_keys($grid[$i][$j])); $k++) {
                    for ($l = min(array_keys($grid[$i][$j][$k])); $l <= max(array_keys($grid[$i][$j][$k])); $l++) {
                        $neighbours = $this->countActiveNeighbours($grid, $i, $j, $k, $l);
                        if ($grid[$i][$j][$k][$l] == true) {
                            $newGrid[$i][$j][$k][$l] = ($neighbours == 2 || $neighbours == 3);
                        } else {
                            $newGrid[$i][$j][$k][$l] = $neighbours == 3;
                        }
                    }
                }
            }
        }

        return $newGrid;
    }

    private function countActiveNeighbours($grid, $x, $y, $z, $w)
    {
        $active = 0;

        for ($i = $x - 1; $i <= $x + 1; $i++) {
            for ($j = $y - 1; $j <= $y + 1; $j++) {
                for ($k = $z - 1; $k <= $z + 1; $k++) {
                    for ($l = $w - 1; $l <= $w + 1; $l++) {
                        if (isset($grid[$i][$j][$k][$l]) && ($i != $x || $j != $y || $k != $z || $l != $w)) {
                            $active += ($grid[$i][$j][$k][$l] == true ? 1 : 0);
                        }
                    }
                }
            }
        }

        return $active;
    }

    private function countActive($grid)
    {
        $active = 0;

        for ($i = min(array_keys($grid)); $i <= max(array_keys($grid)); $i++) {
            for ($j = min(array_keys($grid[$i])); $j <= max(array_keys($grid[$i])); $j++) {
                for ($k = min(array_keys($grid[$i][$j])); $k <= max(array_keys($grid[$i][$j])); $k++) {
                    for ($l = min(array_keys($grid[$i][$j][$k])); $l <= max(array_keys($grid[$i][$j][$k])); $l++) {
                        $active += $grid[$i][$j][$k][$l] == true ? 1 : 0;
                    }
                }
            }
        }

        return $active;
    }
}
