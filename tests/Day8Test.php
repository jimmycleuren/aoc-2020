<?php

namespace App\Tests;

use App\Command\Day8Puzzle1Command;
use App\Command\Day8Puzzle2Command;
use App\DependencyInjection\Computer;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class Day8Test extends KernelTestCase
{
    public function testPuzzle1()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $application->add(new Day8Puzzle1Command(new Computer(new NullLogger())));

        $command = $application->find('day8:puzzle1');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('The value of the accumulator is 1949', $output);
    }

    public function testPuzzle2()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $application->add(new Day8Puzzle2Command(new Computer(new NullLogger())));

        $command = $application->find('day8:puzzle2');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('The value of the accumulator is 2092', $output);
    }
}
