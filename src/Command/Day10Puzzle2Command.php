<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day10Puzzle2Command extends Command
{
    protected static $defaultName = 'day10:puzzle2';

    protected function configure()
    {
        $this
            ->setDescription('Day 10: Puzzle 2')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $contents = file_get_contents(dirname(__DIR__)."../../input/day10.txt");
        $numbers = explode("\n", $contents);
        $numbers = array_merge([0], $numbers, [max($numbers) + 3]);

        sort($numbers);

        //$paths = $this->getPaths($numbers, 0);

        //$io->success("The result is $paths");

        $options = [0, 1, 1, 2, 4, 7, 13];

        $length = 1;
        $lengths = [];
        for ($i = 0; $i < count($numbers) - 1; $i++) {
            if ($numbers[$i+1] == $numbers[$i] + 1) {
                $length++;
            } else {
                if (!isset($lengths[$length])) {
                    $lengths[$length] = 0;
                }
                $lengths[$length]++;
                $length = 1;
            }
        }
        $lengths[$length]++;

        $paths = 1;
        foreach ($lengths as $key => $length) {
            $paths *= pow($options[$key], $length);
        }

        $io->success("The result is $paths");

        return Command::SUCCESS;
    }

    /**
     * Recursive function that takes way too long :)
     * Used it to calculate the number of options per consecutive length
     */
    public function getPaths($numbers, $index)
    {
        $result = 0;
        if ($index == count($numbers) - 1) {
            return 1;
        } elseif ($numbers[$index + 1] > $numbers[$index] + 3) {
            return 0;
        } else {
            if ($numbers[$index + 1] < $numbers[$index] + 4 ) {
                $result += $this->getPaths($numbers, $index + 1);
            }
            if ($index + 2 < count($numbers) && $numbers[$index + 2] < $numbers[$index] + 4 ) {
                $result += $this->getPaths($numbers, $index + 2);
            }
            if ($index + 3 < count($numbers) && $numbers[$index + 3] < $numbers[$index] + 4 ) {
                $result += $this->getPaths($numbers, $index + 3);
            }
        }

        return $result;
    }
}
