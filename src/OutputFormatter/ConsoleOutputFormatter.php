<?php

namespace ConsoleCommander\OutputFormatter;

use ConsoleCommander\OutputFormatter\OutputFormatterInterface;

/**
 * Class OutputFormatter
 *
 * Output formatter for console
 */
class ConsoleOutputFormatter implements OutputFormatterInterface
{
    protected string $buffer;

    public const COLOR_BLACK = 30;
    public const COLOR_RED = 31;
    public const COLOR_GREEN = 32;
    public const COLOR_YELLOW = 33;
    public const COLOR_BLUE = 34;
    public const COLOR_MAGENTA = 35;
    public const COLOR_CYAN = 36;
    public const COLOR_LIGHT_GRAY = 37;
    public const COLOR_DARK_GRAY = 90;
    public const COLOR_LIGHT_RED = 91;
    public const COLOR_LIGHT_GREEN = 92;
    public const COLOR_LIGHT_YELLOW = 93;
    public const COLOR_LIGHT_BLUE = 94;
    public const COLOR_LIGHT_MAGENTA = 95;
    public const COLOR_LIGHT_CYAN = 96;
    public const COLOR_WHITE = 97;

    /**
     * Create a new console formatter instance
     * @return void
     */
    public function __construct()
    {
        $this->buffer = '';
    }

    /**
     * Push text to buffer
     * @param string $content
     * @param string $format
     * @param string $color
     * @return $this
     */
    public function pushText(
        string $content = '',
        string $format = '%s',
        string $color = ConsoleOutputFormatter::COLOR_LIGHT_GRAY
    ): ConsoleOutputFormatter {
        $this->buffer .= sprintf($this->applyColor($format, $color), $content);
        return $this;
    }

    /**
     * Push paragraph to buffer
     * @param string $content
     * @param string $format
     * @param string $color
     * @return $this
     */
    public function pushParagraph(
        string $content = '',
        string $format = '%s',
        string $color = ConsoleOutputFormatter::COLOR_LIGHT_GRAY
    ): ConsoleOutputFormatter {
        $this->buffer .= sprintf($this->applyColor($format, $color), $content);
        return $this->pushLine();
    }

    /**
     * Push line break to buffer
     * @param int $count
     * @return $this
     */
    public function pushLine(int $count = 1) : OutputFormatterInterface
    {
        for ($i = 0; $i<$count; $i++) {
            $this->pushText(PHP_EOL);
        }
        return $this;
    }

    /**
     * Reset buffer
     * @return $this
     */
    public function reset(): OutputFormatterInterface
    {
        $this->buffer = '';
        return $this;
    }

    /**
     * Output buffer
     * @return void
     */
    public function output(): void
    {
        echo $this->buffer;
    }

    /**
     * Apply color to string
     * @param string $format
     * @param string $color
     * @return string
     */
    protected function applyColor(string $format, string $color): string
    {
        return "\033[".$this->defineColor($color)."m".$format." \033[0m";
    }

    /**
     * Define code color
     * @param string $color
     * @return int
     */
    protected function defineColor(string $color) : int
    {
        switch ($color) {
            case OutputFormatterInterface::DANGER_COLOR:
                return self::COLOR_RED;
            case OutputFormatterInterface::IMPORTANT_COLOR:
                return self::COLOR_GREEN;
            case OutputFormatterInterface::DEFAULT_COLOR:
            default:
                return self::COLOR_LIGHT_GRAY;
        }
    }
}
