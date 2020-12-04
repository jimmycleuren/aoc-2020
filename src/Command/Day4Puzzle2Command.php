<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day4Puzzle2Command extends Command
{
    protected static $defaultName = 'day4:puzzle2';

    protected function configure()
    {
        $this
            ->setDescription('Day 4: Puzzle 2')
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
            if ($ok) {
                $byr = $matches2[2][array_search('byr', $matches2[1])];
                if ($byr < 1920 || $byr > 2002) {
                    $ok = false;
                }
                $iyr = $matches2[2][array_search('iyr', $matches2[1])];
                if ($iyr < 2010 || $iyr > 2020) {
                    $ok = false;
                }
                $eyr = $matches2[2][array_search('eyr', $matches2[1])];
                if ($eyr < 2020 || $eyr > 2030) {
                    $ok = false;
                }
                $hgt = $matches2[2][array_search('hgt', $matches2[1])];
                $unit = substr($hgt, -2);
                $val = substr($hgt, 0, -2);
                if (!(($unit == "cm" && $val >= 150 && $val <= 193) || ($unit == "in" && $val >= 59 && $val <= 76))) {
                    $ok = false;
                }
                $hcl = $matches2[2][array_search('hcl', $matches2[1])];
                if (!preg_match("/^\#[0-9a-f]{6}$/", $hcl)) {
                    $ok = false;
                }
                $ecl = $matches2[2][array_search('ecl', $matches2[1])];
                if (!in_array($ecl, ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth'])) {
                    $ok = false;
                }
                $pid = $matches2[2][array_search('pid', $matches2[1])];
                if (!preg_match("/^[0-9]{9}$/", $pid)) {
                    $ok = false;
                }
            }
            $valid += ($ok === true ? 1 : 0);
        }

        $io->success("The number of valid passports is $valid");

        return Command::SUCCESS;
    }
}
