<?php

namespace App\Helpers;

use SimpleXMLElement;

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
