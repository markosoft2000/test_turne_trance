<?php

/**
 * Flight model class
 * Class stores information of the flight table
 * Date: 2016-10-05
 * Author: Mark
 */
class FlightModel extends Model
{
    public $id;
    protected static $idDBFieldName = 'id';
    public $from;
    public $to;
    public $back;
    public $start;
    public $stop;
    public $adult;
    public $child;
    public $infant;
    public $totalPrice;

    /**
     * Returns DB table name for entity.
     * @return string
     */
    public static function getTableName()
    {
        return 'flights';
    }

    /**
     * Get map of table model
     * [DB_table_field => class_property_name]
     * @return array
     */
    public static function getMap() {
        return array(
            static::$idDBFieldName => 'id',
            'from' => 'from',
            'to' => 'to',
            'back' => 'back',
            'start' => 'start',
            'stop' => 'stop',
            'adult' => 'adult',
            'child' => 'child',
            'infant' => 'infant',
            'price' => 'totalPrice'
        );
    }

    /**
     * @param $id
     * @return null|FlightModel|static
     * @throws Exception
     */
    public static function getById($id) {
        return static::getByWhereParam(static::$idDBFieldName, $id);
    }

    /**
     * Saves flight data
     * @return int
     * @throws Exception
     */
    public function save() {
        if (!static ::validateDate($this->start)) {
            throw new Exception('Date incorrect');
        }

        return parent::save();
    }

    /**
     * Date validating function
     * @param $date
     * @return bool
     */
    public static function validateDate($date) {
        $parsedDate = date_parse($date);
        return checkdate($parsedDate['month'], $parsedDate['day'], $parsedDate['year']);
    }
}