<?php

if (! function_exists('config')) {

    /**
     * Get config from file
     *
     * @param $key
     * @return mixed
     */
    function config($key)
    {
        $config = require_once ROOT_DIR . '/config/main.php';

        return isset($config[$key]) ? $config[$key] : null;
    }
}


if (! function_exists('array_to_xml')) {
    /**
     * Convert php array to XMl document
     *
     * @param $data
     * @param $xml
     *
     * @return void
     */
    function array_to_xml($data, &$xml) {
        foreach($data as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)){
                    $subNode = $xml->addChild("$key");
                    array_to_xml($value, $subNode);
                }else{
                    $subNode = $xml->addChild("beer");
                    array_to_xml($value, $subNode);
                }
            }else {
                $xml->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }

}