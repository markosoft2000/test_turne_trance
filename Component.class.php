<?php
/**
 * Created by PhpStorm.
 * User: marko
 * Date: 05.10.16
 * Time: 14:56
 */

class Component
{
    const ACTION_GET_FLIGHT_FROM_XML = 'GET_FLIGHT_FROM_XML';

    public function executeComponent($action) {
        switch ($action) {
            case self::ACTION_GET_FLIGHT_FROM_XML: {
                $xml = file_get_contents('load/data.xml');
                $this->ActionGetFlightFromXML($xml);
                break;
            }
        }

        //render something...
    }

    private function ActionGetFlightFromXML($xml) {
        $searchFlight = new SearchFlight();
        $searchFlight->sendAndSave($xml);
    }
}