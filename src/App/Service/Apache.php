<?php

namespace App\Service;

class Apache
{
    const CMD_DUMP_VHOSTS = 'apachectl -t -D DUMP_VHOSTS';
    const CMD_DUMP_MODULES = 'apachectl -t -D DUMP_MODULES';
    const CMD_DUMP_RUN_CFG = 'apachectl -t -D DUMP_RUN_CFG';
    const CMD_DUMP_VERSION = 'apachectl -v';

    const REGEX_IP_ADDR = '/((\d+)\.){3}(\d+)/';
    const REGEX_PORT = '/port (\d+) namevhost/';
    const REGEX_DOMAIN = '/port \d+ namevhost (.*) \(/';
    const REGEX_VERSION = '/Apache\/([\d\.]*)/';

    const DEFAULT_IP_ADDR = '127.0.0.1';
    const DEFAULT_PORT = '80';

    protected $rawVhosts;
    protected $rawModules;
    protected $rawConfig;
    protected $rawVersion;

    public function __construct()
    {
        $this->initRawVersion();
        $this->initRawVhosts();
        $this->initRawModules();
        $this->initRawConfig();
    }

    protected function initRawVersion()
    {
        if (! $this->rawVersion) {
            $this->rawVersion = $this->runCommand(self::CMD_DUMP_VERSION);
        }
    }

    /**
     * Initializes the rawVhosts property
     */
    protected function initRawVhosts()
    {
        if (! $this->rawVhosts) {
            $this->rawVhosts = $this->runCommand(self::CMD_DUMP_VHOSTS);
        }
    }

    /**
     * Initializes the rawModules property
     */
    protected function initRawModules()
    {
        if (! $this->rawModules) {
            $this->rawModules = $this->runCommand(self::CMD_DUMP_MODULES);
        }
    }

    /**
     * Initializes the rawConfig property
     */
    protected function initRawConfig()
    {
        if (! $this->rawConfig) {
            $this->rawConfig = $this->runCommand(self::CMD_DUMP_RUN_CFG);
        }
    }

    /**
     * Gets the version of apache running.
     *
     * @return string
     *   The version of apache running.
     */
    public function getVersion()
    {
        return $this->getValueByRegex(self::REGEX_VERSION, $this->rawVersion, 1);
    }

    /**
     * Gets the unique IP addresses running on the server.
     *
     * @return array
     *   The IP addresses responding on this server.
     */
    public function getIps()
    {
        $ips = $this->getValueByRegex(self::REGEX_IP_ADDR, $this->rawVhosts, 0);
        if (!count($ips)) {
            $ips = ['127.0.0.1'];
        }
        return array_values(array_unique($ips));
    }

    /**
     * Gets the unique ports running on the server.
     *
     * @return array
     *   The ports the server is listening on.
     */
    public function getPorts()
    {
        $ports = $this->getValueByRegex(self::REGEX_PORT, $this->rawVhosts, 1);
        return array_values(array_unique($ports));
    }

    /**
     * Public endpoint to get VirtualHost information.
     *
     * @return array An array of VirtualHost information.
     */
    public function getVhosts()
    {
        return $this->parseRawVhosts($this->rawVhosts);
    }

    /**
     * Workhorse method to actually get all of the virtualhost data from an input value.
     *
     * @param  string  $input
     *   The output of apachectl.
     *
     * @return array
     *   An associative array of domains, and ip:port info.
     */
    protected function parseRawVhosts($input)
    {
        $ip      = '';
        $port    = '';
        $domain  = '';
        $results = [];

        $lines   = explode(PHP_EOL, $input);
        foreach ($lines as $line) {
            if (preg_match(self::REGEX_IP_ADDR, $line, $matches)) {
                $ip = $matches[0]; continue;
            }

            if (preg_match(self::REGEX_PORT, $line, $matches)) {
                $port = $matches[1];
            }

            if (preg_match(self::REGEX_DOMAIN, $line, $matches)) {
                $domain = $matches[1];
            }

            if (! $domain) {
                continue;
            }

            $ip = $ip ? $ip : '127.0.0.1';

            $results[$domain][] = "{$ip}:{$port}";
        }

        return $results;
    }

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
