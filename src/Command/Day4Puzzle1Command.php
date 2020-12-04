<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day4Puzzle1Command extends Command
{
    protected static $defaultName = 'day4:puzzle1';

    protected function configure()
    {
        $this
            ->setDescription('Day 4: Puzzle 1')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $required = ['byr','iyr','eyr','hgt','hcl','ecl','pid'];

        $contents = file_get_contents(dirname(__DIR__)."../../input/day4.txt")." ";
        preg_match_all("/((\w+):([\w\d\#]+)\s)+/", $contents, $matches);

        $valid = 0;
        foreach ($matches[0] as $passport) {
            preg_match_all("/(\w+):([\w\d\#]+)\s/", $passport, $matches2);
            $ok = true;
            foreach ($required as $key) {
                if (!in_array($key, $matches2[1])) {
                    $ok = false;
                    break;
                }
            }
            $valid += ($ok === true ? 1 : 0);
        }

        $io->success("The number of valid passports is $valid");

        return Command::SUCCESS;
    }
}
