<?php

Asset::push('js', 'application');
Asset::push('css', 'application');

if(isset($_SERVER['HTTP_HOST'])){
    $parts = explode('.', $_SERVER['HTTP_HOST']);
    $app->host = array_shift($parts);
    $app->domain = implode('.', $parts);
}

