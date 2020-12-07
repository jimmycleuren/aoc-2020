<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day7Puzzle2Command extends Command
{
    protected static $defaultName = 'day7:puzzle2';

    protected function configure()
    {
        $this
            ->setDescription('Day 7: Puzzle 2')
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

        $result = $this->getInnerBags("shiny gold", $list);

        $io->success("The number of options is ".$result);

        return Command::SUCCESS;
    }

    private function getInnerBags($color, $list)
    {
        $result = 0;

        foreach ($list[$color] as $key => $item) {
            $result += $item['amount'];
            $result += $item['amount'] * $this->getInnerBags($item['color'], $list);
        }

        return $result;
    }
}
