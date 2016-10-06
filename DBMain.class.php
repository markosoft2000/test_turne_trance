<?php
/**
 * DB Main configuration class
 * Class stores configuration settings for DB connection
 * Date: 2016-10-05
 * Author: Mark
 */

class DBMain extends DB
{
    protected static $name = 'main';
    protected static $dbConfig = array(
        'HOST' => 'localhost',
        'USER_NAME' => 'root',
        'USER_PASSWORD' => '230023',
        'DB_NAME' => 'turne_trance',
        'CHARSET' => 'utf8'
    );
}

