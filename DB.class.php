<?php
/**
 * Data base connection multiton storage class
 * Class stores singleton DB connection
 * Date: 2016-10-05
 * Author: Mark
 */

abstract class DB
{
    // create this section in a inherited class
    protected static $name;
    /**
     * DB config data
     * @var array $dbConfig
     */
    protected static $dbConfig = array(
        'HOST' => '',
        'USER_NAME' => '',
        'USER_PASSWORD' => '',
        'DB_NAME' => '',
        'CHARSET' => ''
    );

    // private service section
    //==========================================
    /**
     * @var $instances
     */
    protected static $instances = array();

    /**
     * private constructor
     */
    private function __construct() {}

    /**
     * Private clone function - no clone available
     */
    private function __clone() {}

    /**
     * @return mysqli
     * @throws Exception mysqli connect_error
     */
    public static function getInstance() {
        if (!Helper::getArrayElement(static::$instances, static::$name, null)) {
            $mysqli = new mysqli(static::getHost(), static::getUserName(), static::getUserPassword(), static::getDBName());

            if ($mysqli->connect_error) {
                throw new Exception($mysqli->connect_error, $mysqli->connect_errno);
            }

            if (static::getCharset()) {
                $mysqli->set_charset(static::getCharset());
            }

            static::$instances[static::$name] = $mysqli;
        }

        return static::$instances[static::$name];
    }

    public static function dropInstance() {
        if (isset(static::$instances[static::$name])) {
            static::$instances[static::$name]->close();
            unset(static::$instances[static::$name]);
            return static::$instances[static::$name];
        }
    }

    private static function getHost() {
        return static::$dbConfig['HOST'];
    }

    private static function getUserName() {
        return static::$dbConfig['USER_NAME'];
    }

    private static function getUserPassword() {
        return static::$dbConfig['USER_PASSWORD'];
    }

    private static function getDBName() {
        return static::$dbConfig['DB_NAME'];
    }

    private static function getCharset() {
        return static::$dbConfig['CHARSET'];
    }
}

