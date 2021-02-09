<?php

namespace ConsoleCommander;

use ConsoleCommander\Exceptions\InvalidArgumentException;
use ConsoleCommander\OutputFormatter\OutputFormatterInterface;

/**
 * Class Kernel
 *
 * Kernel of ConsoleCommander library
 */
class Kernel
{
    /**
     * @var \ConsoleCommander\CommandMap
     */
    protected CommandMap $commands;

    /**
     * Instance of the formatter
     * @var \ConsoleCommander\OutputFormatter\OutputFormatterInterface
     */
    protected OutputFormatterInterface $formatter;

    /**
     * Create the kernel instance
     * @param $directory
     * @param $namespace
     * @param \ConsoleCommander\OutputFormatter\OutputFormatterInterface $formatter
     * @throws \ReflectionException
     */
    public function __construct($directory, $namespace, OutputFormatterInterface $formatter)
    {
        $this->commands = new CommandMap($directory, $namespace);
        $this->formatter = $formatter;
    }

    /**
     * Run console command
     * @param string|null $name
     * @param array $parameters
     * @return int
     * @throws \ConsoleCommander\Exceptions\InvalidArgumentException
     */
    public function run(string|null $name = '', $parameters = [])
    {
        if (empty($name)) {
            return $this->help();
        } elseif (!empty($parameters) && $parameters[0] === '{help}') {
            return $this->description($name);
        } else {
            try {
                return $this->commands->get($name)->run($parameters);
            } catch (InvalidArgumentException $e) {
                $this->formatter
                    ->pushLine()
                    ->pushText($e->getMessage(), '%s', OutputFormatterInterface::DANGER_COLOR)
                    ->pushLine()
                    ->output();
                return 0;
            }
        }
    }

    /**
     * Get formatted list of commands
     * @return int
     * @throws \ReflectionException
     */
    public function help(): int
    {
        $max_name_len = 0;
        foreach ($this->commands as $name => $command) {
            $max_name_len = max(strlen($name), $max_name_len);
        }
        $max_name_len += 2;

        $this->formatter->pushLine();
        foreach ($this->commands as $name => $command) {
            $this->formatter
                ->pushText($name, "%-".$max_name_len."s", OutputFormatterInterface::IMPORTANT_COLOR)
                ->pushText($command->getDescription())
                ->pushLine();
        }
        $this->formatter->output();
        return 1;
    }

    /**
     * Get formatted description of a command
     * @param string $name
     * @return string
     * @throws \ReflectionException
     */
    public function description(string $name)
    {
        $command = $this->commands->get($name);
        $this->formatter
            ->pushLine()
            ->pushText($name, "%s", OutputFormatterInterface::IMPORTANT_COLOR)
            ->pushText($command->getDescription())
            ->pushLine(2)
            ->pushText('Arguments:')
            ->pushLine();

        $max_name_len = 0;
        foreach ($command->getArguments() as $name => $desc) {
            $max_name_len = max(strlen($name), $max_name_len);
        }

        foreach ($command->getOptions() as $name => $desc) {
            $max_name_len = max(strlen($name), $max_name_len);
        }
        $max_name_len += 2;

        foreach ($command->getArguments() as $name => $desc) {
            $this->formatter
                ->pushText($name, "%-".$max_name_len."s", OutputFormatterInterface::IMPORTANT_COLOR)
                ->pushText($desc)
                ->pushLine();
        }
        $this->formatter
            ->pushLine()
            ->pushText('Options:')
            ->pushLine();

        foreach ($command->getOptions() as $name => $desc) {
            $this->formatter
                ->pushText($name, "%-".$max_name_len."s", OutputFormatterInterface::IMPORTANT_COLOR)
                ->pushText($desc)
                ->pushLine();
        }
        $this->formatter->output();
        return 1;
    }
}
