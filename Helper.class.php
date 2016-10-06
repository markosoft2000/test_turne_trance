<?php
/**
 * Helper class
 * Class contents common functions for whole project
 * Date: 2016-10-05
 * Author: Mark
 */

class Helper {
    /**
     * safe get array element
     * @param array $array
     * @param mixed $key
     * @param mixed $defaultValue
     * @return mixed
     */
    public static function getArrayElement(array $array, $key, $defaultValue = null) {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        return $defaultValue;
    }
}