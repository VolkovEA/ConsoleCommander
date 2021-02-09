<?php

namespace ConsoleCommander\OutputFormatter;

/**
 * Interface OutputFormatterInterface
 */
interface OutputFormatterInterface
{
    public const DEFAULT_COLOR = "DEFAULT_COLOR";
    public const IMPORTANT_COLOR = "IMPORTANT_COLOR";
    public const DANGER_COLOR = "DANGER_COLOR";

    /**
     * Push text to buffer
     * @param string $content
     * @param string $format
     * @param string $color
     * @return \ConsoleCommander\OutputFormatter\OutputFormatterInterface
     */
    public function pushText(
        string $content,
        string $format = '%s',
        string $color = self::DEFAULT_COLOR
    ): OutputFormatterInterface;

    /**
     * Push paragraph to buffer
     * @param string $content
     * @param string $format
     * @param string $color
     * @return \ConsoleCommander\OutputFormatter\OutputFormatterInterface
     */
    public function pushParagraph(
        string $content,
        string $format = '%s',
        string $color = self::DEFAULT_COLOR
    ): OutputFormatterInterface;

    /**
     * Push line break to buffer
     * @param int $count
     * @return \ConsoleCommander\OutputFormatter\OutputFormatterInterface
     */
    public function pushLine(int $count = 1): OutputFormatterInterface;

    /**
     * Reset buffer
     * @return \ConsoleCommander\OutputFormatter\OutputFormatterInterface
     */
    public function reset(): OutputFormatterInterface;

    /**
     * Output buffer
     * @return void
     */
    public function output(): void;
}
