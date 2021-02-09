<?php

namespace ConsoleCommander;

use ReflectionClass;
use ConsoleCommander\Exceptions\InvalidArgumentException;
use ConsoleCommander\Exceptions\LogicException;

/**
 * Class CommandMap
 */
class CommandMap implements \Iterator, \Countable
{
    /**
     * Position of the iterator
     * @var int
     */
    private int $position;

    /**
     * Array of the iterator
     * @var array
     */
    private array $array = [];

    /**
     * Keys of the iterator
     * @var array
     */
    private array $keys;


    /**
     * Create a new command map instance
     * @param string $directory
     * @param string $namespace
     * @return void
     * @throws \ReflectionException
     */
    public function __construct(string $directory, string $namespace)
    {
        $this->load($directory, $namespace);
        $this->keys = array_keys($this->array);
        $this->position = 0;
    }

    /**
     * Load commands to array
     * @param string $directory
     * @param string $namespace
     * @return void
     * @throws \ReflectionException
     */
    protected function load(string $directory, string $namespace): void
    {
        foreach (scandir($directory) as $file) {
            if ($file !== '.' && $file !== '..' && !is_dir($file)) {
                $file = $namespace.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    DIRECTORY_SEPARATOR . $file
                );
                $reflectionClass = new ReflectionClass($file);
                if ($reflectionClass->isSubclassOf(Command::class) && !$reflectionClass->isAbstract()) {
                    $this->push(new CommandContainer($reflectionClass));
                }
            }
        }
    }

    /**
     * Push a command to array
     * @param \ConsoleCommander\CommandContainer $commandContainer
     * @return $this
     * @throws \ConsoleCommander\Exceptions\LogicException|\ReflectionException
     */
    protected function push(CommandContainer $commandContainer): CommandMap
    {
        if (!isset($this->array[$commandContainer->getName()])) {
            $this->array[$commandContainer->getName()] = $commandContainer;
        } else {
            throw new LogicException('Command "'.$commandContainer->getName().'" already exists');
        }
        return $this;
    }

    /**
     * Get command by name
     * @param $name
     * @return \ConsoleCommander\CommandContainer
     * @throws \ConsoleCommander\Exceptions\InvalidArgumentException
     */
    public function get($name): CommandContainer
    {
        if (isset($this->array[$name])) {
            return $this->array[$name];
        } else {
            throw new InvalidArgumentException('Command "'.$name.'" does not exist');
        }
    }

    /**
     * Get names of registered commands
     * @return array
     */
    public function getNames(): array
    {
        return $this->keys;
    }

    /**
     * Get all registered commands
     * @return array
     */
    public function getAll(): array
    {
        return $this->array;
    }

    /**
     * Get current element of the iterator
     * @return CommandContainer
     */
    public function current(): CommandContainer
    {
        return $this->array[$this->key()];
    }

    /**
     * Change position to next
     * @return void
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Get current key of the Iterator
     * @return string
     */
    public function key()
    {
        return $this->keys[$this->position];
    }

    /**
     * Check valid of the current position
     * @return bool
     */
    public function valid()
    {
        return isset($this->keys[$this->position]);
    }

    /**
     * Reset of the Iterator
     */
    public function rewind()
    {
        $this->position = 0;
    }


    /**
     * Count all commands
     * @return int
     */
    public function count()
    {
        return count($this->getNames());
    }
}
