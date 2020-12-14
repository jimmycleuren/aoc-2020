<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day14Puzzle1Command extends Command
{
    protected static $defaultName = 'day14:puzzle1';

    protected function configure()
    {
        $this
            ->setDescription('Day 14: Puzzle 1')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $contents = file_get_contents(dirname(__DIR__)."../../input/day14.txt");
        preg_match_all("/mask = [\w]+(\smem\[[\d]+\] = \d+)+/", $contents, $programs);

        $results = [];
        foreach ($programs[0] as $program) {
            preg_match("/mask = (?P<mask>[\w]+)/", $program, $mask);
            $mask = $mask['mask'];

            preg_match_all("/mem\[(?P<address>[\d]+)\] = (?P<value>\d+)/", $program, $instructions);

            foreach ($instructions['address'] as $key => $address) {
                $value = $instructions['value'][$key];

                $results[$address] = $this->applyBitmask($value, $mask);
            }
        }

        $total = array_sum($results);

        $io->success("The sum of all values is $total");

        return Command::SUCCESS;
    }

    private function applyBitmask($value, $mask)
    {
        $value = str_pad(decbin($value), 36, '0', STR_PAD_LEFT);
        $result = [];

        for ($i = 0; $i < 36; $i++) {
            if ($mask[$i] == 'X') {
                $result[$i] = $value[$i];
            } else {
                $result[$i] = $mask[$i];
            }
        }

        return bindec(implode("", $result));
    }
}
