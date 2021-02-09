<?php

namespace ConsoleCommander;

/**
 * Class Command
 *
 * Base abstract class for all commands
 */
abstract class Command
{
    /**
     * The name of the command
     * @var string
     */
    protected string $name = '';

    /**
     * The command description
     * @var string
     */
    protected string $description = '';

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
        $this->options = [];
        $this->arguments = [];
    }

    /**
     * Execute the console command
     * @return int
     */
    abstract public function execute(): int;

    /**
     * Push option to options array
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function pushOptions(string $name, $value): void
    {
        if (isset($this->options[$name])) {
            $this->options[$name] = is_array($this->options[$name]) ? $this->options[$name] : [$this->options[$name]];
            $value = is_array($value) ? $value : [$value];
            $this->options[$name] = array_merge($this->options, $value);
        } else {
            $this->options[$name] = $value;
        }
    }

    /**
     * Push argument to arguments array
     * @param mixed $value
     */
    public function pushArguments($value): void
    {
        $value = is_array($value) ? $value : [$value];
        $this->arguments = array_merge($this->arguments, $value);
    }
}
