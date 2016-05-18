<?php

class Helper
{

    public static function userName($id)
    {
        $user = UserProfile::where('user_id', $id)->first();
        return ucwords($user->first_name . ' ' . $user->last_name);
    }

    public static function FormattedDate($date = null)
    {
        if (!isset($date)) {
            $date = time();
        } else {
            $date = strtotime($date);
        }

        return date('Y-m-d', $date);
    }

    protected static $noonce;
    public static function noonce()
    {
        if (!self::$noonce) {
            self::$noonce = uniqid();
        }

        return self::$noonce;
    }

    static $_cache = [];

    static $_scripts = ['/app/app.js'];
    public static function pageScripts()
    {
        return self::$_scripts;
    }

    public static function pageScript($script = null)
    {
        \Log::info($script);
        if ($script) {
            self::$_scripts[] = $script;
        }
        return;
    }

    public static function currency($num)
    {
        return number_format($num, 2, '.', ',');
    }

    public static function userArray($users = null)
    {
        if (is_array($users)) {
            $dbUsers = User::whereIn('id', $users)->get();
        } else {
            $dbUsers = User::all();
        }
        $users = [];
        foreach ($dbUsers as $user) {
            $users[$user->id] = $user->profile->first_name . ' ' . $user->profile->last_name;
        }
        return $users;
    }

    public static function resizeImage($folder, $ext, $name, $width)
    {
        ini_set("memory_limit", "1024M");
        set_time_limit(0);

        $original = $folder . '/original.' . $ext;

        $thumb = new Imagick($original);

        $height = ($thumb->getImageHeight() / $thumb->getImageWidth()) * $width;

        $width2 = $width / 2;
        $height2 = ($thumb->getImageHeight() / $thumb->getImageWidth()) * $width2;

        $thumb->thumbnailImage($width, $height, true);
        $thumb->setImageDepth(8);
        $thumb->setCompression(Imagick::COMPRESSION_JPEG);
        $thumb->setCompressionQuality(60);
        $thumb->setImageUnits(72);
        $thumb->stripImage();
        $thumb->writeImage($folder . '/' . $name . '.' . $ext);

        $thumb->destroy();
    }

    public static function resizeImage2($source, $dest, $width)
    {
        ini_set("memory_limit", "1024M");
        set_time_limit(0);

        $thumb = new Imagick($source);

        $height = ($thumb->getImageHeight() / $thumb->getImageWidth()) * $width;

        $thumb->thumbnailImage($width, $height, true);
        $thumb->setImageDepth(8);
        $thumb->setCompression(Imagick::COMPRESSION_JPEG);
        $thumb->setCompressionQuality(60);
        $thumb->setImageUnits(72);
        $thumb->stripImage();
        $thumb->writeImage($dest);

        $thumb->destroy();

    }

    public static function Timeago($date)
    {
        return \Carbon\Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();
    }

    public static function encrypt($item)
    {
        $encrypter = new Illuminate\Encryption\Encrypter(Config::get('app.key'));
        $encrypted = $encrypter->encrypt(json_encode($item));
        return self::urlsafe_b64encode($encrypted);
    }

    public static function decrypt($item)
    {
        $encrypter = new Illuminate\Encryption\Encrypter(Config::get('app.key'));
        $decrypted = $encrypter->decrypt(self::urlsafe_b64decode($item));
        return json_decode($decrypted);
    }

    public static function urlsafe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    public static function urlsafe_b64decode($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    public static function isRouteAction($route, $true = 'active', $false = '')
    {
        if (Route::currentRouteAction() === $route) {
            return $true;
        }

        return $false;
    }

    public static function currency_format($number, $not_for_input = true)
    {
        $locale = app()->user_locale;
        $number = number_format((double) $number, $locale->decimal_places, $locale->decimal_separator, ($not_for_input ? $locale->thousand_separator : ''));
        if ($not_for_input) {
            if ($locale->symbol_location === 'before') {
                return $locale->currency_symbol . ' ' . $number;
            }

            return $number . ' ' . $locale->currency_symbol;
        }
        return $number;
    }

    public static function short_date_to_time($date)
    {
        $locale = app()->user_locale;
        return DateTime::createFromFormat($locale->php_short_date, $date)->format('U');
    }

    public static function long_date_to_time($date)
    {
        $locale = app()->user_locale;
        return DateTime::createFromFormat($locale->php_long_date, $date)->format('U');
    }

    public static function timestamp($date)
    {
        $locale = app()->user_locale;
        return date($locale->php_short_date . ' ' . $locale->php_time, strtotime($date));
    }

    public static function today_short_date()
    {
        return date(app()->user_locale->php_short_date);
    }

    public static function short_date_to_mysql($date)
    {
        return date('Y-m-d', Helper::short_date_to_time($date));
    }

    public static function mysql_to_short_date($date)
    {
        return date(app()->user_locale->php_short_date, strtotime($date));
    }
}
