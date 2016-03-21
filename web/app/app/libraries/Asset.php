<?php

class Asset {
    private static $queued = [
        'css' => [],
        'js' => [],
    ];

    public static function push($type, $path) {
        array_push(self::$queued[$type], $path);
    }

    public static function unshift($type, $path) {
        array_unshift(self::$queued[$type], $path);
    }

    public static function tags($type) {
        $returnString = '';
        foreach (self::$queued[$type] as $path) {
            if (!App::environment('local')) {
                $path = $path . '.min';
            }

            if ($type === 'js') {
                $returnString = $returnString . '      <script src="/assets/js/' . $path . '.js"></script>' . "\n";
            } else {
                $returnString = $returnString . '      <link rel="stylesheet" href="/assets/css/' . $path . '.css">' . "\n";
            }
        }
        return $returnString;
    }
}