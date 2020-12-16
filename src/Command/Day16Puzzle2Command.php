<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day16Puzzle2Command extends Command
{
    protected static $defaultName = 'day16:puzzle2';

    public function __construct(LoggerInterface $logger, string $name = null)
    {
        $this->logger = $logger;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Day 16: Puzzle 2')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->logger->info("Parsing input");
        $blocks = explode("\n\n", file_get_contents(dirname(__DIR__)."../../input/day16.txt"));

        $valid = [];
        $rules = [];
        foreach (explode("\n", $blocks[0]) as $rule) {
            preg_match_all("/(\d+)-(\d+)/", $rule, $matches);
            preg_match("/([\w\s]+):/", $rule, $name);
            $name = $name[1];
            foreach ($matches[1] as $key => $start) {
                $stop = $matches[2][$key];
                for ($i = $start; $i <= $stop; $i++) {
                    $valid[] = $i;
                    $rules[$name][] = $i;
                }
            }
        }

        $valid = array_unique($valid);

        $this->logger->info("Removing invalid tickets");
        $validTickets = [];
        foreach (array_slice(explode("\n", $blocks[2]), 1) as $nearby) {
            $ok = true;
            foreach (explode(",", $nearby) as $number) {
                if (!in_array($number, $valid)) {
                    $ok = false;
                    break;
                }
            }
            if ($ok) {
                $validTickets[] = explode(",", $nearby);
            }
        }

        $this->logger->info("Summarizing possible options");
        $fieldOptions = [];
        foreach ($validTickets as $key => $values) {
            foreach ($values as $key2 => $value) {
                foreach ($rules as $ruleName => $rule) {
                    if (in_array($value, $rule)) {
                        $fieldOptions[$key][$key2][] = $ruleName;
                    }
                }
            }
        }

        $this->logger->info("Calculating intersects");
        $intersects = [];
        for ($i = 0; $i < count($fieldOptions[0]); $i++) {
            $intersects[$i] = $fieldOptions[0][$i];
            foreach ($fieldOptions as $ticket) {
                $intersects[$i] = array_intersect($intersects[$i], $ticket[$i]);
            }
        }


        $this->logger->info("Reducing intersects");
        $result = [];
        while (count($intersects) > 0) {
            $found = null;
            foreach ($intersects as $key => $intersect) {
                if (count($intersect) == 1) {
                    $found = array_values($intersect)[0];
                    if (stristr($found, "departure")) {
                        $result[$key] = $found;
                    }
                    unset($intersects[$key]);
                    break;
                }
            }
            foreach ($intersects as $key => $intersect) {
                unset($intersects[$key][array_search($found, $intersect)]);
            }
        }

        $myTicket = explode(",", explode("\n", $blocks[1])[1]);

        $this->logger->info("Calculating result");
        $product = 1;
        foreach ($result as $key => $rule) {
            $product *= $myTicket[$key];
        }

        $io->success("The product is ".$product);

        return Command::SUCCESS;
    }
}
