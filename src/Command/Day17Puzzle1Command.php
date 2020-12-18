<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day17Puzzle1Command extends Command
{
    protected static $defaultName = 'day17:puzzle1';

    public function __construct(LoggerInterface $logger, string $name = null)
    {
        $this->logger = $logger;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Day 17: Puzzle 1')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $lines = explode("\n", file_get_contents(dirname(__DIR__)."../../input/day17.txt"));

        $grid = [];

        for ($i = -20; $i < 20; $i++) {
            for ($j = -20; $j < 20; $j++) {
                for ($k = -20; $k < 20; $k++) {
                    $grid[$i][$j][$k] = false;
                }
            }
        }

        foreach ($lines as $i => $line) {
            foreach (str_split($line) as $j => $char) {
                $grid[$i][$j][0] = ($char == '#');
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
                    $neighbours = $this->countActiveNeighbours($grid, $i, $j, $k);
                    if ($grid[$i][$j][$k] == true) {
                        $newGrid[$i][$j][$k] = ($neighbours == 2 || $neighbours == 3);
                    } else {
                        $newGrid[$i][$j][$k] = $neighbours == 3;
                    }
                }
            }
        }

        return $newGrid;
    }

    private function countActiveNeighbours($grid, $x, $y, $z)
    {
        $active = 0;

        for ($i = $x - 1; $i <= $x + 1; $i++) {
            for ($j = $y - 1; $j <= $y + 1; $j++) {
                for ($k = $z - 1; $k <= $z + 1; $k++) {
                    if (isset($grid[$i][$j][$k]) && ($i != $x || $j != $y || $k != $z)) {
                        $active += ($grid[$i][$j][$k] == true ? 1 : 0);
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
                    $active += $grid[$i][$j][$k] == true ? 1 : 0;
                }
            }
        }

        return $active;
    }
}
