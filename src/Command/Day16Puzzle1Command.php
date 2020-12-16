<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day16Puzzle1Command extends Command
{
    protected static $defaultName = 'day16:puzzle1';

    protected function configure()
    {
        $this
            ->setDescription('Day 16: Puzzle 1')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $blocks = explode("\n\n", file_get_contents(dirname(__DIR__)."../../input/day16.txt"));

        $valid = [];
        foreach (explode("\n", $blocks[0]) as $rule) {
            preg_match_all("/(\d+)-(\d+)/", $rule, $matches);
            foreach ($matches[1] as $key => $start) {
                $stop = $matches[2][$key];
                for ($i = $start; $i <= $stop; $i++) {
                    $valid[] = $i;
                }
            }
        }

        $valid = array_unique($valid);

        $result = 0;
        foreach (array_slice(explode("\n", $blocks[2]), 1) as $nearby) {
            foreach (explode(",", $nearby) as $number) {
                if (!in_array($number, $valid)) {
                    $result += $number;
                }
            }
        }

        $io->success("The sum is ".$result);

        return Command::SUCCESS;
    }
}
