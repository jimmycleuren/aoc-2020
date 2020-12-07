<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day7Puzzle1Command extends Command
{
    protected static $defaultName = 'day7:puzzle1';

    protected function configure()
    {
        $this
            ->setDescription('Day 7: Puzzle 1')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $contents = file_get_contents(dirname(__DIR__)."../../input/day7.txt")." ";
        preg_match_all("/(?P<color>(\w+ \w+)) bags contain (?P<contents>(((\d+ \w+ \w+ bag(s)*)|no other bags)[,.]( )*)+)/", $contents, $matches);

        $list = [];
        foreach ($matches[0] as $key => $value) {
            $color = $matches['color'][$key];
            preg_match_all("((?P<amount>\d+) (?P<color>\w+ \w+) bag)", $matches['contents'][$key], $matches2);

            $temp = [];
            foreach ($matches2[0] as $key2 => $item) {
                $temp[] = ['amount' => $matches2['amount'][$key2], 'color' => $matches2['color'][$key2],];
            }
            $list[$color] = $temp;
        }

        $result = $this->getOuterBags("shiny gold", $list);

        $result = array_unique($result);

        $io->success("The number of options is ".count($result));

        return Command::SUCCESS;
    }

    private function getOuterBags($color, $list)
    {
        $result = [];
        foreach ($list as $key => $item) {
            foreach ($item as $child) {
                if ($child['color'] == $color) {
                    $result[] = $key;
                    $result = array_merge($result, $this->getOuterBags($key, $list));
                }
            }
        }

        return $result;
    }
}
