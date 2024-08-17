<?php

namespace App\Helpers;

use SimpleXMLElement;

/**
 * Class wrapper for simplexml_load_file
 */
class XmlLoader
{
    /**
     * @param $filePath
     * @return SimpleXMLElement|false
     */
    public function load($filePath): SimpleXMLElement|false
    {
        return simplexml_load_file($filePath);
    }
}
