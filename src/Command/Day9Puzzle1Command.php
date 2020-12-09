<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day9Puzzle1Command extends Command
{
    protected static $defaultName = 'day9:puzzle1';

    protected function configure()
    {
        $this
            ->setDescription('Day 9: Puzzle 1')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $contents = file_get_contents(dirname(__DIR__)."../../input/day9.txt");
        $numbers = explode("\n", $contents);

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
                $io->success("The first number that is not the sum of 2 of the previous 25 numbers is ".$numbers[$i]);
                break;
            }
        }

        return Command::SUCCESS;
    }
}
