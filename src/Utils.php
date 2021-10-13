<?php

namespace Prettus\FIQL;

/**
 * @author Anderson Andrade <contact@andersonandra.de>
 */
class Utils
{
    /**
     * @param $array
     * @return array
     */
    public static function array_flatten($array): array
    {
        if (!is_array($array)) {
            return [];
        }

        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, self::array_flatten($value));
            } else {
                $result = array_merge($result, array($key => $value));
            }
        }
        return $result;
    }
}
