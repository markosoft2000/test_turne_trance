<?php

/**
 * Flight management class
 * Class provides methods to load, search ... flights
 * Date: 2016-10-06
 * Author: Mark
 */
class SearchFlight
{
    protected $data;

    /**
     * short call method
     * @param $xml
     */
    public function sendAndSave($xml) {
        $this->parseFlightFromXML($xml);
        $this->saveFlight();
    }

    /**
     * Parsing xml data to an array
     * @param SimpleXMLElement $xmlData
     */
    protected function parseFlightFromXML($xmlData)
    {
        //parse - in xml - out data
        $this->data = array();

        if ($xmlData->Result == 'SUCCESS') {
            foreach ($xmlData->ShopOptions as $shopOptions) {
                $dataItem = array('ID' => '');

                foreach ($shopOptions->ShopOption as $shopOption) {
                    foreach ($shopOption->ItineraryOptions->ItineraryOption as $itineraryOption) {
                        //cities of Departure and Arrival
                        $from = (string)$itineraryOption['From'];
                        $to = (string)$itineraryOption['To'];
                        $dataItem['FROM'] = empty($dataItem['FROM']) ? $from : $dataItem['FROM'];
                        $dataItem['TO'] = empty($dataItem['TO']) ? $to : $dataItem['TO'];

                        if ($dataItem['FROM'] == $to && $dataItem['TO'] == $from) {
                            $dataItem['BACK'] = 1;
                        }

                        //time of Departure and Arrival
                        foreach ($itineraryOption->FlightSegment as $flightSegment) {
                            $timeStart = date('Y-m-d', strtotime((string)$flightSegment->Departure['Time']));
                            $timeStop = date('Y-m-d', strtotime((string)$flightSegment->Arrival['Time']));

                            //if date is wrong it's sets to a 0 timestamp value (1970-01-01) - it's an error
                            if ($timeStart == '1970-01-01' || $timeStop == '1970-01-01') {
                                continue 3;//skip this whole flight
                            }

                            $dataItem['TIME_START'] = empty($dataItem['TIME_START']) ? $timeStart : $dataItem['TIME_START'];
                            $dataItem['TIME_STOP'] = empty($dataItem['BACK']) ? $timeStop : $dataItem['TIME_STOP'];

                            //flight info
//                            $dataItem['AIR_LINE'] = (string) $flightSegment['Airline'];
//                            $dataItem['FLIGHT'] = (string) $flightSegment['Flight'];
                        }
                    }

                    //fare info
                    $dataItem['TOTAL_PRICE'] = 0.0;

                    foreach ($shopOption->FareInfo->Fares->Fare as $fare) {
                        $dataItem['TOTAL_PRICE'] += (float)$fare->Price['Total'];

                        switch ((string)$fare->PaxType['AgeCat']) {
                            case 'ADT': {
                                $dataItem['ADULT'] = (string)$fare->PaxType['Count'];
                                break;
                            }
                            case 'CLD': {
                                $dataItem['CHILD'] = (string)$fare->PaxType['Count'];
                                break;
                            }
                            case 'INF': {
                                $dataItem['INFANT'] = (string)$fare->PaxType['Count'];
                                break;
                            }
                        }
                    }
                }

                $this->data[] = $dataItem;
                unset($dataItem);
            }
        }
    }

    /**
     * Saves flight data to the DB
     * @return void
     */
    protected function saveFlight() {
        if (!$this->data) {
            return;
        }

        foreach ($this->data as $item) {
            $item['FROM'] = Helper::getArrayElement($item, 'FROM', '') ? AirportModel::getAirport($item['FROM']) : '';
            $item['TO'] = Helper::getArrayElement($item, 'TO', '') ? AirportModel::getAirport($item['TO']) : '';

            try {
                $flight = new FlightModel();
                $flight->id = '';
                $flight->from = Helper::getArrayElement($item, 'FROM', '');
                $flight->to = Helper::getArrayElement($item, 'TO', '');
                $flight->start = Helper::getArrayElement($item, 'TIME_START', '');
                $flight->stop = Helper::getArrayElement($item, 'TIME_STOP', '');
                $flight->back = Helper::getArrayElement($item, 'BACK', '');
                $flight->adult = Helper::getArrayElement($item, 'ADULT', '');
                $flight->child = Helper::getArrayElement($item, 'CHILD', '');
                $flight->infant = Helper::getArrayElement($item, 'INFANT', '');
                $flight->totalPrice = Helper::getArrayElement($item, 'TOTAL_PRICE', '');
                $flightId = $flight->save();

                //optional
                $flight = FlightModel::getById($flightId);

                if ($flight) {
                    var_dump($flight);
                }
            }
            catch (Exception $e) {
                var_dump($e->getMessage());
            }
        }
    }
}