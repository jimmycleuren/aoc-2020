<?php

namespace App\Command;

use App\DependencyInjection\Computer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Day8Puzzle1Command extends Command
{
    protected static $defaultName = 'day8:puzzle1';
    private $computer;

    public function __construct(Computer $computer, string $name = null)
    {
        $this->computer = $computer;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Day 8: Puzzle 1')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $contents = file_get_contents(dirname(__DIR__)."../../input/day8.txt");
        $this->computer->load($contents);

        $done = [];
        $accumulator = 0;
        $this->computer->run(function($params) use(&$done, &$accumulator) {
            if (in_array($params['programCounter'], $done)) {
                return true;
            } else {
                $done[] = $params['programCounter'];
                $accumulator = $params['accumulator'];

                return false;
            }
        });

        $io->success("The value of the accumulator is ".$accumulator);

        return Command::SUCCESS;
    }
}
