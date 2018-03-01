<?php

namespace App;

use GetOpt\GetOpt;
use GetOpt\Option;

class App
{
    protected $limit;
    protected $format;

    /**
     * App constructor.
     * Parse input params from cli.
     *
     */
    public function __construct()
    {
        //create input params
        $optionLimit = new Option('l', 'limit', GetOpt::REQUIRED_ARGUMENT);
        $optionLimit->setValidation('is_numeric');
        $optionLimit->setDefaultValue(10);
        $optionFormat = new Option('f', 'format', GetOpt::REQUIRED_ARGUMENT);
        $optionFormat->setDefaultValue('json');

        $getOpt = new GetOpt([$optionLimit, $optionFormat]);

        //parse params
        try {
            $getOpt->process();
        } catch (\Exception $e) {
            self::cliLog('Invalid option provided');
        }

        $this->limit = $getOpt->getOption('limit');
        $this->format = $getOpt->getOption('format');
    }

    /**
     * Get beers from API
     * Save to file
     */
    public function run()
    {
        $beer = new Beer($this->limit, $this->format);
        $beers = $beer->getItems();
        $beers->save();
    }

    /**
     * Display message on CLI and exit if needed
     *
     * @param $message
     * @param bool $exit
     */
    public static function cliLog($message, $exit = true)
    {
        echo $message . PHP_EOL;

        if ($exit) exit;
    }

}