<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day18Puzzle2Command extends Command
{
    protected static $defaultName = 'day18:puzzle2';

    public function __construct(LoggerInterface $logger, string $name = null)
    {
        $this->logger = $logger;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Day 18: Puzzle 2')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $lines = explode("\n", file_get_contents(dirname(__DIR__)."../../input/day18.txt"));

        $total = 0;
        foreach ($lines as $line) {
            $line = str_replace("(", " ( ", $line);
            $line = str_replace(")", " ) ", $line);
            $line = str_replace("  ", " ", $line);
            $line = trim($line);
            $total += $this->calculate($line);
        }

        $io->success("The total is $total");

        return Command::SUCCESS;
    }

    private function calculate($input)
    {
        $result = null;
        $operation = null;

        while (preg_match_all("/\(([\d\s\+\*]+)\)/", $input, $matches)) {
            foreach($matches[1] as $match) {
                $input = str_replace('('.$match.')', $this->calculate($match), $input);
            }
        }

        while (preg_match_all("/(\d+) \+ (\d+)/", $input, $matches)) {
            foreach($matches[0] as $key => $match) {
                $input = str_replace($match, $matches[1][$key] + $matches[2][$key], $input);
            }
        }

        $input = explode(" ", trim($input));

        $result = null;
        foreach ($input as $value) {
            switch ($value) {
                case '+':
                case '*':
                    break;
                default:
                    if ($result === null) {
                        $result = $value;
                    } else {
                        $result *= $value;
                    }
                    break;
            }
        }

        return $result;
    }
}
