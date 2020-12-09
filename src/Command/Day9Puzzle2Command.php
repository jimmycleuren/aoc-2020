<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day9Puzzle2Command extends Command
{
    protected static $defaultName = 'day9:puzzle2';

    protected function configure()
    {
        $this
            ->setDescription('Day 9: Puzzle 2')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $contents = file_get_contents(dirname(__DIR__)."../../input/day9.txt");
        $numbers = explode("\n", $contents);

        $invalid = 0;
        for ($i = 25; $i < count($numbers); $i++) {
            $previous = array_slice($numbers, $i - 25, $i - 1);
            $ok = false;
            foreach ($previous as $number1) {
                foreach ($previous as $number2) {
                    if ($number1 + $number2 == $numbers[$i]) {
                        $ok = true;
                        break;
                    }
                }
                if ($ok) {
                    break;
                }
            }
            if (!$ok) {
                $invalid = $numbers[$i];
                break;
            }
        }

        for ($i = 0; $i < count($numbers); $i++) {
            $total = $numbers[$i];
            for ($j = $i + 1; $j < count($numbers); $j++) {
                $total += $numbers[$j];
                if ($total > $invalid) {
                    break;
                }
                if ($total == $invalid) {
                    $result = array_slice($numbers, $i, $j - $i);
                    $io->success("The sequence is ".implode(", ", $result).", min is ".min($result).", max is ".max($result).", the solution is ".(min($result)+max($result)));
                    break;
                }
            }
        }

        return Command::SUCCESS;
    }
}
