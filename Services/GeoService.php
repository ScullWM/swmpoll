<?php

namespace Services;

class GeoService {

    /**
     * Check if GPS Coords look similar to distinct error data
     *
     * @version 02-12-13
     * @param  Float  $latE Latitude from Etablissement
     * @param  Float  $lonE Longitude from Etablissement
     * @param  Float  $latV Latitude from Town
     * @param  Float  $lonV Longitude from Town
     * @return boolean  if data are strange return false
     */
    public function isGeoSimilar($latE, $lonE, $latV, $lonV)
    {
        if(substr($latE, 0, 4)==substr($latV, 0, 4)) return true;
        if(substr($lonE, 0, 3)==substr($lonV, 0, 3)) return true;
        return false;
    }

    /**
     * Get Minimal and maximal Lat & Long for input data
     *
     * @version  02-12-13
     * @param  Integer $lat  Lat source data
     * @param  Integer $lon  Lon source data
     * @return Array         Values
     */
    public function getMinMaxCoords($lat, $lon, $rad = '20')
    {
        $R   = 6371; // earth's mean radius, km
        $maxLat = $lat + rad2deg($rad/$R);
        $minLat = $lat - rad2deg($rad/$R); // compensate for degrees longitude getting smaller with increasing latitude
        $maxLon = $lon + rad2deg($rad/$R/cos(deg2rad($lat)));
        $minLon = $lon - rad2deg($rad/$R/cos(deg2rad($lat)));
        $maxLat=number_format((float)$maxLat, 8, '.', '');
        $minLat=number_format((float)$minLat, 8, '.', '');
        $maxLon=number_format((float)$maxLon, 8, '.', '');
        $minLon=number_format((float)$minLon, 8, '.', '');

        return array('maxLat'=>$maxLat, 'minLat'=>$minLat, 'maxLon'=>$maxLon, 'minLon'=>$minLon);
    }

    /**
     * For each array row get the distance and affect it
     *
     * @version  03-12-13
     * @param  array  $data   You data array must contain object only
     * @param  string $keytab Identifier to use
     * @param  float  $LatSrc Coords from origin point
     * @param  float  $LonSrc Coords from origin point
     * @param  string $libLat Field name
     * @param  string $libLon Field name
     * @return array          Id=>floated distance
     */
    public function getDistanceForEtablissement($data, $keytab = 'id', $LatSrc, $LonSrc, $libLat = 'latitude', $libLon = 'longitude')
    {
        $distance = array();
        foreach ($data as $d) {
            $distance[$d->$keytab] = $this->geoGetDistance($LatSrc, $LonSrc, $d->$libLat, $d->$libLon, 'K');
        }
        return $distance;
    }

    /**
     * Get distance between to geocoords using great circle distance formula
     *
     * @version 02-12-13
     * @param float $lat1
     * @param float $lat2
     * @param float $lon1
     * @param float $lon2
     * @param float $unit   M=miles, K=kilometers, N=nautical miles, I=inches, F=feet
     * @return float
     */
    public static function geoGetDistance($lat1, $lon1, $lat2, $lon2, $unit='K') {
        
      // calculate miles
      $M =  69.09 * rad2deg(acos(sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($lon1 - $lon2)))); 

      switch(strtoupper($unit))
      {
        case 'K':
          // kilometers
          return $M * 1.609344;
          break;
        case 'N':
          // nautical miles
          return $M * 0.868976242;
          break;
        case 'F':
          // feet
          return $M * 5280;
          break;            
        case 'I':
          // inches
          return $M * 63360;
          break;            
        case 'M':
        default:
          // miles
          return $M;
          break;
      }
    }    
}