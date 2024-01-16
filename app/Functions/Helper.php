<?php

namespace App\Functions;

use Illuminate\Support\Str;

class Helper {

  public static function generateSlug($string, $model) {
    $slug = Str::slug($string, '-');
    $original_slug = $slug;

    $exists = $model::where('slug', $slug)->first();
    $c = 1;

    while ($exists) {
      $slug = $original_slug .'-'. $c;
      $exists = $model::where('slug', $slug)->first();
      $c++;
    }

    return $slug;
  }

  public static function generateFullAddress($street_address, $street_number,$cap,$city,$province,$region,$country) {

    return trim($street_address
    . " " .
    $street_number
    . " " .
    $cap
    . " " .
    $city
    . " " .
    $province
    . " " .
    $region
    . " " .
    $country);

  }

  public static function generateLatLng($address, $lat, $lon) {
    $apiUrlStart = 'https://api.tomtom.com/search/2/geocode/';
    $apiUrlEnd = '.json?key=nq7V1UsXc4xKYSFcXm3BDbYjtFObpZl8';
    $url = $apiUrlStart . urlencode($address) . $apiUrlEnd;


    $response = file_get_contents($url);

    if ($response === false) {
        echo 'Errore nella richiesta HTTP';
    } else {
        $data = json_decode($response, true);
        $positionLat = $data['results'][0]['position'][$lat];
        $positionLon = $data['results'][0]['position'][$lon];
        $positionLatLng = array(
            $lat => $positionLat,
            $lon => $positionLon
        );

        // $positionLatLng[] = $positionLat;
        // $positionLatLng[] = $positionLon;
        $freeFormAddress = $data['results'][0]['address']['freeformAddress'];
    }


    return [$positionLatLng,$freeFormAddress];
}


    public static function getAddressDatas($lat, $lon) {
        $apiUrlStart = 'https://api.tomtom.com/search/2/reverseGeocode/';
        $apiUrlEnd = '.json?key=nq7V1UsXc4xKYSFcXm3BDbYjtFObpZl8';
        $url = $apiUrlStart . $lat . ',' . $lon . $apiUrlEnd;

        $response = file_get_contents($url);

        if ($response === false) {
            echo 'Errore nella richiesta HTTP';
        } else {
            $data = json_decode($response, true);

            $api_address_response = $data['addresses'][0]['address'];
        }

        return $api_address_response;

    }

}
