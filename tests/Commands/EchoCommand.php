<?php

namespace Tests\Commands;

use ConsoleCommander\Command;

class EchoCommand extends Command
{
    /**
     * The name of the command
     * @var string
     */
    protected string $name = 'echo_command';

    /**
     * The command description
     * @var string
     */
    protected string $description = 'Print list of options and arguments';

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
     * @return void
     */
    public function execute(): int
    {
        $out = '';
        $out .= sprintf('Arguments:'.PHP_EOL);
        foreach ($this->arguments as $item) {
            $out .= sprintf('  -  %s'.PHP_EOL, $item);
        }

        $out .= sprintf('Options:'.PHP_EOL);
        foreach ($this->options as $name => $option) {
            $out .= sprintf('  -  %s'.PHP_EOL, $name);
            $option = is_array($option) ? $option : [$option];
            foreach ($option as $item) {
                $out .= sprintf('     -  %s'.PHP_EOL, $item);
            }
        }
        echo $out;
        return 1;
    }
}
