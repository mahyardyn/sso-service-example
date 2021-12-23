<?php

namespace App\Lib\Logger;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Logger
{
    private static string $salt = 'NetBee';

    /**
     * Manages logs more easily
     *
     * @param string $type error | info | warning
     * @param string $name name must include in config/logging.php
     * @param array|string $message it must be 1d arrays of messages or a string message
     * @return void
     */
    public static function set(string $type, string $name, array|string $message, string|null $url = null)
    {
        list($ip, $mobile, $fields, $message, $url) = self::init($message, $url);

        // combine all the values
        $log = $ip . $mobile . $url . $fields . $message;

        // Trim log and replace two space with one
        self::fixLog($log);

        // Encrypt $log with hash_hmac
        $hash = self::hash($log);

        // Store log and hashed log with Laravel logging system
        Log::channel($name)->$type("$log | $hash");
    }

    /**
     * Implode multidimensional arrays
     *
     * @param array $array
     * @return string
     */
    private static function array_implode(array $array): string
    {
        $string = array();
        foreach ($array as $key => $val) {

            if (is_array($val)) {
                $val = implode(',', $val);
            }

            $string[] = is_numeric($key) ? "$val" : "{$key}[{$val}]";
        }
        $result = implode(', ', $string);
        return (is_numeric(array_key_first($array)) and sizeof($array) > 1) ? sprintf("[%s]", $result) : $result;
    }

    /**
     * Encrypt input string
     *
     * @param string $message
     * @return string
     */
    private static function hash(string $message): string
    {
        $salt = self::$salt . Carbon::now();
        return hash_hmac('sha256', $message, $salt);
    }

    /**
     * @param array|string $message
     * @param string|null $url
     * @return array
     */
    private static function init(array|string $message, ?string $url): array
    {
        // Get user IP from request and add to string - Example -> IP: 127.0.01
        $ip = sprintf("IP: %s - ", request()->ip());

        // Get user Mobile from sso service and add to string - Example -> Mobile: 09123456789
        $mobile = auth()->user() ? sprintf("Mobile: %s -", auth()->user()->mobile) : '';

        // add all passed fields to string - Example -> Fields: url[test.ir], mobiles[10,20]
        $fields = request()->all() ? " Fields: " . self::array_implode(request()->all()) . " -" : '';

        // add all passed messages to string - Example -> Messages: [test1, test2]
        $message = is_array($message) ? (sizeof($message) > 1 ? " Messages: " . self::array_implode($message) : " Message: $message[0]") : " Message: $message";

        // add url parameter to string - Example -> url: test.com/api/test
        $url = $url ? " URL: $url -" : '';

        return array($ip, $mobile, $fields, $message, $url);
    }

    /**
     * Trim log and replace two space with one
     *
     * @param string $log
     * @return void
     */
    private static function fixLog(string &$log): void
    {
        $log = trim($log);
        $log = str_replace('  ', ' ', $log);
    }
}
