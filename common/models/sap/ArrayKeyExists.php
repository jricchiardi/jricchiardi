<?php

namespace common\models\sap;

trait ArrayKeyExists
{
    /**
     * Checks if multiple keys exist in an array
     *
     * @param array $array
     * @param array|string $keys
     *
     * @return bool
     */
    public static function array_keys_exist(array $array, $keys)
    {
        $count = 0;
        if (!is_array($keys)) {
            $keys = func_get_args();
            array_shift($keys);
        }
        foreach ($keys as $key) {
            if (isset($array[$key]) || array_key_exists($key, $array)) {
                $count++;
            }
        }

        return count($keys) === $count;
    }
}
