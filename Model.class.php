<?php

/**
 * DB model class
 * Class provide common methods for inherited table Models
 * Date: 2016-10-05
 * Author: Mark
 */
abstract class Model
{
    /**
     * Returns DB table name for entity.
     * @return string
     */
    public static function getTableName()
    {
        return '';
    }

    /**
     * Returns table fields which bound to the class
     * @return array
     */
    public static function getMap() {
        return array();
    }

    /**
     * Search record by given parameters
     * @param $fieldName
     * @param $fieldValue
     * @return null|FlightModel|AirportModel|static
     * @throws Exception
     */
    protected static function getByWhereParam($fieldName, $fieldValue) {
        $db = DBMain::getInstance();
        $queryStr = 'SELECT * FROM `' . static::getTableName() . '` WHERE `' . $fieldName . "` = '" . $fieldValue . "' LIMIT 1";

        if ($query = $db->query($queryStr)) {
            if ($result = $query->fetch_assoc()) {
                $record = new static();
                $map = $record::getMap();

                foreach ($map as $field => $property) {
                    $record->$property = $result[$field];
                }

                return $record;
            }
        }

        return null;
    }

    /**
     * Saves record to the DB
     * @return int
     * @throws Exception
     */
    public function save() {
        /** @var static Model|FlightModel|AirportModel*/
        if (!empty($this->id) && static::getById($this->id)) {
            return 0;
        }

        $map = static::getMap();
        $fields = array();
        $values= array();

        foreach ($map as $field => $property) {
            $fields[] = "`$field`";
            $values[] = property_exists($this, $property) ? "'{$this->$property}'" : '';
        }

        if (count($fields) && count($values)) {
            $db = DBMain::getInstance();
            $queryStr = 'INSERT INTO `' . static::getTableName() . '` (' . implode(',', $fields) . ') ' .
                        'VALUES (' . implode(',', $values) . ');';
            $query = $db->query($queryStr);
            return $query ? $db->insert_id : 0;
        }

        return 0;
    }

    /**
     * Updates record in the DB
     * @return int
     */
    public function update() {
        /** @var $this Model|FlightModel */
        /** @var static Model|FlightModel */
        if (empty($this->id)) {
            return $this->save();
        } else {
            //check if the record exists
            if (static::getById($this->id)) {
                //update
                return $this->id;
            } else {//else save the record
                return $this->save();
            }
        }
    }
}