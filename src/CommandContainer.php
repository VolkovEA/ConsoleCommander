<?php

namespace ConsoleCommander;

use ReflectionClass;
use ConsoleCommander\Exceptions\InvalidArgumentException;

/**
 * Class CommandContainer
 */
class CommandContainer
{
    /**
     * The command ReflectionClass instance
     * @var \ReflectionClass
     */
    protected ReflectionClass $reflectionClass;

    /**
     * The command instance
     * @var Command
     */
    protected Command $command;

    /**
     * Create a new command container instance
     * @param \ReflectionClass $class
     */
    public function __construct(ReflectionClass $class)
    {
        $this->reflectionClass = $class;
    }

    /**
     * Get name of the command
     * @return string
     * @throws \ReflectionException
     */
    public function getName(): string
    {
        $name = trim($this->reflectionClass->getProperty('name')->getDefaultValue());
        if ($name == '') {
            $name = explode('\\', $this->reflectionClass->getName());
            $name = array_pop($name);
            preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $name, $matches);
            foreach ($matches[0] as &$match) {
                $match = $match === strtoupper($match) ?: strtolower($match);
            }
            return implode('_', $matches[0]);
        } else {
            return $name;
        }
    }

    /**
     * Get options description of the command
     * @return array
     * @throws \ReflectionException
     */
    public function getOptions(): array
    {
        return $this->reflectionClass->getProperty('options')->getDefaultValue();
    }

    /**
     * Get arguments description of the command
     * @return array
     * @throws \ReflectionException
     */
    public function getArguments(): array
    {
        return $this->reflectionClass->getProperty('arguments')->getDefaultValue();
    }

    /**
     * Get description of the command
     * @return string
     * @throws \ReflectionException
     */
    public function getDescription(): string
    {
        return $this->reflectionClass->getProperty('description')->getDefaultValue();
    }

    /**
     * Execute the command
     * @param $args
     * @throws \Exception
     * @return int
     */
    public function run($args): int
    {
        $this->command = new $this->reflectionClass->name();
        $this->setInput($args);
        return $this->command->execute();
    }

    /**
     * Set input options and arguments
     * @param $args
     * @throws InvalidArgumentException
     * @return void
     */
    protected function setInput($args): void
    {
        foreach ($args as $arg) {
            if (preg_match('/^\[[A-Za-z0-9_]+=.+]$/', $arg)) {
                $data = explode('=', substr($arg, 1, -1), 2);
                $name = $data[0];
                $value = $data[1];
                if (preg_match('/^{.+}$/', $value)) {
                    $value = explode(',', substr($value, 1, -1));
                }
                $this->command->pushOptions($name, $value);
            } elseif (preg_match('/^{.+}$/', $arg)) {
                $value = explode(',', substr($arg, 1, -1));
                $this->command->pushArguments($value);
            } elseif (preg_match('/^[A-Za-z0-9_]+$/', $arg)) {
                $this->command->pushArguments($arg);
            } else {
                throw new InvalidArgumentException('Invalid syntax near "'.$arg.'"');
            }
        }
    }
}
