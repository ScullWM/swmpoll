<?php

namespace Services\Base;

use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\MapTypeId;
use Ivory\GoogleMap\Helper\MapHelper;
use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Overlays\InfoWindow;
use Ivory\GoogleMap\Events\MouseEvent;

class BaseMapService {

    public function getBasicMap($lat = 0, $lon = 0, $marker, $dimWidth = 500, $dimHeight = 500)
    {
        $map = new Map();
        //$map->addMarker($marker);
        $map->setPrefixJavascriptVariable('map_');
        $map->setHtmlContainerId('map_canvas');

        $map->setCenter($lat, $lon, false);
        $map->setAutoZoom(false);
        $map->setMapOption('zoom', 12);

        $map->setStylesheetOptions(array(
            'width'  => $dimWidth,
            'height' => $dimHeight,
        ));

        $map->setLanguage('fr');

        return $map;
    }

    /**
     * Set a basic Marker with data
     *
     * @version  03-12-13
     * @param  float $latitude  Marker latitude
     * @param  float $longitude Marker longitude
     */
    public function setMarker($latitude, $longitude)
    {
        $marker = new Marker();
        // Configure your marker options
        $marker->setPrefixJavascriptVariable('marker_');
        $marker->setPosition($latitude, $longitude, true);

        $marker->setOption('clickable', false);
        $marker->setOption('flat', true);
        $marker->setOptions(array(
            'clickable' => true,
            'flat'      => true,
        ));
        $marker->setAnimation(Animation::DROP);
        $marker->setIcon('http://maps.gstatic.com/mapfiles/markers/marker.png');

        return $marker;
    }

    /**
     * Set an info windows with html text
     *
     * @version  06-12-13
     * @param  float $latitude  Marker latitude
     * @param  float $longitude Marker longitude
     */
    public function setInfoWindow($latitude, $longitude, $nom)
    {
        $infoWindow = new InfoWindow();

        // Configure your info window options
        $infoWindow->setPrefixJavascriptVariable('info_window_'.rand(99,999));
        $infoWindow->setPosition($latitude, $longitude, true);
        $infoWindow->setPixelOffset(1.1, 2.1, 'px', 'pt'); //1.1, 2.1
        $infoWindow->setContent('<p>'.$nom.'</p>');
        $infoWindow->setOpen(false);
        $infoWindow->setAutoOpen(true);
        $infoWindow->setOpenEvent('click');
        $infoWindow->setAutoClose(false);
        $infoWindow->setOption('disableAutoPan', true);
        $infoWindow->setOption('zIndex', 10);
        $infoWindow->setOptions(array(
            'disableAutoPan' => true,
            'zIndex'         => 10,
        ));
        return $infoWindow;
    }

    /**
     * MapService, it's your fucking job, not Controller job!
     *
     * @version  03-12-13
     * @return MapHelper To access rendering method of Map()
     */
    public function getMapHelper()
    {
        return new MapHelper();
    }
}