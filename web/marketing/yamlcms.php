<?php

require 'vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;

class YamlCMS {

    public static function load($file)
    {
        return Yaml::parse($file);
    }
}
