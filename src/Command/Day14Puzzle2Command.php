<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day14Puzzle2Command extends Command
{
    protected static $defaultName = 'day14:puzzle2';

    protected function configure()
    {
        $this
            ->setDescription('Day 14: Puzzle 2')
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

                foreach ($this->getAddresses($address, $mask) as $addr) {
                    $results[$addr] = $value;
                }
            }
        }

        $total = array_sum($results);

        $io->success("The sum of all values is $total");

        return Command::SUCCESS;
    }

    private function getAddresses($address, $mask)
    {
        $address = str_pad(decbin($address), 36, '0', STR_PAD_LEFT);
        $result = [];
        $permutations = pow(2, count_chars($mask)[ord('X')] ?? 0);
        $currentPermutation = $permutations / 2;

        for ($j = 0; $j < $permutations; $j++) {
            $result[$j] = [];
        }

        for ($i = 0; $i < 36; $i++) {
            switch ($mask[$i]) {
                case '0':
                    for ($j = 0; $j < $permutations; $j++) {
                        $result[$j][$i] = $address[$i];
                    }
                    break;
                case '1':
                    for ($j = 0; $j < $permutations; $j++) {
                        $result[$j][$i] = 1;
                    }
                    break;
                case 'X':
                    $current = '0';
                    $counter = 0;
                    for ($j = 0; $j < $permutations; $j++) {
                        $result[$j][$i] = $current;
                        $counter++;
                        if ($counter == $currentPermutation) {
                            $current = $current == '0' ? '1' : '0';
                            $counter = 0;

                        }
                    }
                    $currentPermutation /= 2;
                    break;
            }
        }

        return array_map(function($val) { return bindec(implode("", $val)); }, $result);
    }
}
