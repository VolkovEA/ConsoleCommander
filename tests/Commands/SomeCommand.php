<?php

namespace Tests\Commands;

use ConsoleCommander\Command;

/**
 * Class SomeCommand
 *
 * Example command
 */
class SomeCommand extends Command
{
    /**
     * The name of the command
     * @var string
     */
    protected string $name = 'some_command';

    /**
     * The command description
     * @var string
     */
    protected string $description = 'Example of command';

    /**
     * The command options
     * @var array
     */
    protected array $options = [
        'first_option' => 'First option',
        'second_option' => 'Second option',
    ];

    /**
     * The command arguments
     * @var array
     */
    protected array $arguments = [
        'first_argument' => 'First argument',
        'second_argument' => 'Second argument',
    ];

    /**
     * Create a new command instance
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command
     * @return int
     */
    public function execute(): int
    {
        return 1;
    }
}
