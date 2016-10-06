<?php
/**
 * DB secondary configuration class
 * Class stores configuration settings for DB connection
 * Date: 2016-10-05
 * Author: Mark
 */

class DBSecond extends DB
{
    protected static $name = 'second';
    protected static $dbConfig = array(
        'HOST' => 'localhost',
        'USER_NAME' => 'root',
        'USER_PASSWORD' => '230023',
        'DB_NAME' => 'some other db name',
        'CHARSET' => 'uft8'
    );
}

