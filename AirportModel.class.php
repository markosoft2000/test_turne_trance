<?php

/**
 * Airport model class
 * Class stores information of the airport table
 * Date: 2016-10-05
 * Author: Mark
 */
class AirportModel extends Model
{
    public $id;
    protected static $idDBFieldName = 'id';
    public $code;
    protected static $codeDBFieldName = 'code';
    public $name;
    public $countryName;

    /**
     * Returns DB table name for entity.
     * @return string
     */
    public static function getTableName()
    {
        return 'airports';
    }

    /**
     * Get map of table model - binds DB field names with class properties
     * [table_field => class_property_name]
     * @return array
     */
    public static function getMap() {
        return array(
            static::$idDBFieldName => 'id',
            static::$codeDBFieldName => 'code',
            'name' => 'name',
            'country' => 'countryName'
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
     * @param $value
     * @return AirportModel|null
     */
    public static function getByCode($value) {
        return static::getByWhereParam(static::$codeDBFieldName, $value);
    }

    public function save() {
        return parent::save();
    }

    /**
     * Gets or creates airport record by code
     * @param $code
     * @return int - id of an airport record
     */
    public static function getAirport($code) {
        $airport = static::getByCode($code);
        if ($airport) {
            $id = $airport->id;
            unset($airport);
            return $id;
        } else {
            $airport = new static();
            $airport->id= '';
            $airport->code = $code;
            $airport->name = 'airport_' . rand(1,300);
            $airport->countryName = rand(1,300);
            $id = $airport->save();
            unset($airport);
            return $id;
        }
    }
}