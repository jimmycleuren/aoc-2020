<?php

namespace App\DependencyInjection;

use Psr\Log\LoggerInterface;

class Computer
{
    private $logger;

    private $programCounter = 0;
    private $program = [];
    private $accumulator = 0;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function load(string $code)
    {
        $this->programCounter = 0;
        $this->accumulator = 0;
        unset($this->program);

        $lines = explode("\n", $code);
        foreach ($lines as $line) {
            $parts = explode(" ", $line);
            $this->program[] = [
                'instruction' => $parts[0],
                'params' => [$parts[1]],
            ];
        }
    }

    public function run($callback = null)
    {
        $interrupted = false;

        while(!$interrupted) {
            $instruction = $this->program[$this->programCounter];

            if ($callback) {
                $interrupted = $callback([
                    'programCounter' => $this->programCounter,
                    'accumulator' => $this->accumulator
                ]);
            }

            switch ($instruction['instruction']) {
                case "acc":
                    $this->accumulator += $instruction['params'][0];
                    break;
                case "jmp":
                    $this->programCounter += $instruction['params'][0] - 1;
                    break;
                case "nop":
                    break;
                default:
                    throw new \RuntimeException("Unknown instruction ".$instruction['instruction']);
            }

            $this->programCounter++;

            if ($this->programCounter == count($this->program)) {
                $this->logger->info("Program exited correctly");
                return [
                    'accumulator' => $this->accumulator
                ];
            }
        }
    }
}