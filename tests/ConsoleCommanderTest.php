<?php

namespace Tests;

/**
 * Class ConsoleCommanderTest
 */
class ConsoleCommanderTest extends TestCase
{
    /**
     * Run help info test
     */
    public function testHelp()
    {
        ob_start();
        $this->kernel->run();
        $output = ob_get_clean();

        $this->assertThat(
            $output,
            $this->logicalAnd(
                $this->stringContains('some_command'),
                $this->stringContains('echo_command'),
                $this->stringContains('unnamed_command'),
            )
        );
    }

    /**
     * Run command description test
     */
    public function testDescription()
    {
        ob_start();
        $this->kernel->run('some_command', ['{help}']);
        $output = ob_get_clean();

        $this->assertThat(
            $output,
            $this->logicalAnd(
                $this->stringContains('first_option'),
                $this->stringContains('First option'),
                $this->stringContains('first_argument'),
                $this->stringContains('First argument'),
            )
        );
    }

    /**
     * Run echo command test
     */
    public function testEcho()
    {
        ob_start();
        $this->kernel->run('echo_command', [
            '{verbose,overwrite}',
            '[log_file=app.log]',
            '{unlimited}',
            '[methods={create,update,delete}]',
            '[paginate=50]',
            '{log}'
        ]);
        $output = ob_get_clean();
        $this->assertThat(
            $output,
            $this->logicalAnd(
                $this->stringContains(' verbose'.PHP_EOL),
                $this->stringContains(' overwrite'.PHP_EOL),
                $this->stringContains(' unlimited'.PHP_EOL),
                $this->matchesRegularExpression('/-\s{2}log_file\s+-\s{2}app.log/'),
                $this->matchesRegularExpression('/-\s{2}methods\s+-\s{2}create\s+-\s{2}update\s+-\s{2}delete/'),
                $this->matchesRegularExpression('/-\s{2}paginate\s+-\s{2}50/')
            )
        );
    }
}
