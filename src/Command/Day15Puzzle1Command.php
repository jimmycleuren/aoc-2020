<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day15Puzzle1Command extends Command
{
    protected static $defaultName = 'day15:puzzle1';

    protected function configure()
    {
        $this
            ->setDescription('Day 15: Puzzle 1')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $list = explode(',', file_get_contents(dirname(__DIR__)."../../input/day15.txt"));

        for ($i = count($list); $i < 2020; $i++) {
            $last = $list[$i - 1];
            $occurences = count(array_keys($list, $last));
            if ($occurences == 1) {
                $list[$i] = 0;
            } else {
                $keys = array_keys($list, $last);
                $list[$i] = ($i - 1) - $keys[count($keys) - 2];
            }
        }

        $io->success("The 2020th number is ".$list[2019]);

        return Command::SUCCESS;
    }
}
