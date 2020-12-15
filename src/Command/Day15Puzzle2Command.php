<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day15Puzzle2Command extends Command
{
    protected static $defaultName = 'day15:puzzle2';

    protected function configure()
    {
        $this
            ->setDescription('Day 15: Puzzle 2')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        ini_set('memory_limit', "2048M");

        $list = explode(',', file_get_contents(dirname(__DIR__)."../../input/day15.txt"));

        $occurences = [];
        foreach ($list as $key => $value) {
            $occurences[$value][] = $key;
        }

        $last = $list[count($list) - 1];

        for ($i = count($list); $i < 30000000; $i++) {
            if (!isset($occurences[$last])) {
                $occurences[$last] = [];
            }
            if (count($occurences[$last]) == 1) {
                $value = 0;0;
            } else {
                $length = count($occurences[$last]);
                $value = ($i - 1) - $occurences[$last][$length - 2];
            }
            $occurences[$value][] = $i;
            $last = $value;
            if(count($occurences[$value]) > 2) {
                array_shift($occurences[$last]);
            }
        }

        $io->success("The 30000000th number is ".$last);

        return Command::SUCCESS;
    }
}
