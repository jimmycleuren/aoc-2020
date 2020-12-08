<?php

namespace App\Command;

use App\DependencyInjection\Computer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day8Puzzle2Command extends Command
{
    protected static $defaultName = 'day8:puzzle2';
    private $computer;

    public function __construct(Computer $computer, string $name = null)
    {
        $this->computer = $computer;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Day 8: Puzzle 2')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $contents = file_get_contents(dirname(__DIR__)."../../input/day8.txt");

        $lines = explode("\n", $contents);
        foreach ($lines as $key => $line) {
            if (substr($line, 0, 3) == "nop" || substr($line, 0, 3) == "jmp") {
                $new = $lines;
                $new[$key] = substr($line, 0, 3) == "nop" ? str_replace("nop", "jmp", $new[$key]) : str_replace("jmp", "nop", $new[$key]);
                $this->computer->load(implode("\n", $new));

                $done = [];
                $result = $this->computer->run(function($params) use(&$done, &$accumulator) {
                    if (in_array($params['programCounter'], $done)) {
                        return true;
                    } else {
                        $done[] = $params['programCounter'];
                        return false;
                    }
                });

                if ($result) {
                    $io->success("The value of the accumulator is " . $result['accumulator']);
                }
            }
        }

        return Command::SUCCESS;
    }
}
