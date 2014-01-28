<?php

namespace Services;

use Services\Base\BaseMapService;

class MapService extends BaseMapService {

    /**
     * From a fleuriste collection result get all markers
     *
     * @version  06-12-13
     * @param array $fleuristes Came from a collection
     * @param bool $isInfoWindow Must I show an infowindows
     * @return array List of marker() instance
     */
    public function setFleuristesMarkers($fleuristes, $isInfoWindow = true)
    {
        $markers = array();
        foreach ($fleuristes as $fleuriste) {
            $m = $this->setMarker($fleuriste->ets->latitude, $fleuriste->ets->longitude);
            // do I insert a infoWindow
            if($isInfoWindow) $m->setInfoWindow($this->setInfoWindow($fleuriste->ets->latitude, $fleuriste->ets->longitude, $fleuriste->ets->raison_sociale));
            $markers[] = $m;
        }
        return (array)$markers;
    }

    /**
     * From a fleuriste AR get marker
     *
     * @version  06-01-14
     * @param array  $fleuristes Came from a collection
     * @param bool   $isInfoWindow Must I show an infowindows
     * @return array List of marker() instance
     */
    public function setFleuristeMarker($fleuriste, $isInfoWindow = true)
    {
        $markers = array();
        $m = $this->setMarker($fleuriste->ets->latitude, $fleuriste->ets->longitude);
        // do I insert a infoWindow
        if($isInfoWindow) $m->setInfoWindow($this->setInfoWindow($fleuriste->ets->latitude, $fleuriste->ets->longitude, $fleuriste->ets->raison_sociale));
        $markers[] = $m;

        return (array)$markers;
    }

    /**
     * From a fleuriste collection result get all InfoWindows
     *
     * @version  06-12-13
     * @param array $fleuristes Came from a collection
     * @return array List of marker() instance
     */
    public function setFleuristesInfoWindow($fleuristes)
    {
        $windows = array();
        foreach ($fleuristes as $fleuriste) {
            $windows[] = $this->setInfoWindow($fleuriste->ets->latitude, $fleuriste->ets->longitude);
        }
        return (array)$windows;
    }

    /**
     * Get a simple map show
     *
     * @version  17-01-14
     * @param float  $latitude  Latitude wanted
     * @param float  $longitude Longitude wanted
     * @param boolean $isMarker  Should i display a marker
     */
    public function setSimpleMap($latitude, $longitude, $isMarker = true)
    {
        $map = $this->getBasicMap($latitude, $longitude, array(), '100%', '600px');
        if($isMarker) {
            $marker = $this->setMarker($latitude, $longitude);
            $map->addMarker($marker);
        }
        return $map;
    }
}