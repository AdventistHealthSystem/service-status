<?php

namespace ServerStatus;

abstract class AbstractService
{
    /**
     * Utility method to encapsulate calls to preg_match_all.
     *
     * @param  string  $pattern
     *   The regex pattern to use for searching.
     * @param  string  $input
     *   The string to search through.
     * @param  integer $index
     *   The index of the matched array to use for the results.
     *
     * @return array
     *    The array of results.
     */
    protected function getValueByRegex($pattern, $input, $index = 0)
    {
        preg_match_all($pattern, $input, $results);
        $results = array_values(array_unique($results[$index]));
        return $results;
    }

    /**
     * Utility function to encapsultae calls to shell_exec.
     *
     * @param  string $command
     *   The command to run.
     *
     * @return string
     *   The results of the command.
     */
    protected function runCommand($command)
    {
        return shell_exec($command);
    }
}
