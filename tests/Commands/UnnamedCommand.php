<?php

namespace Tests\Commands;

use ConsoleCommander\Command;

class UnnamedCommand extends Command
{
    /**
     * The command description
     * @var string
     */
    protected string $description = 'Unnamed command';

    /**
     * The command options
     * @var array
     */
    protected array $options = [];

    /**
     * The command arguments
     * @var array
     */
    protected array $arguments = [];

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
