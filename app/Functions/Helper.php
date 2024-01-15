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

  public static function generateLatLng($address, $position) {
    $apiUrlStart = 'https://api.tomtom.com/search/2/geocode/';
    $apiUrlEnd = '.json?key=nq7V1UsXc4xKYSFcXm3BDbYjtFObpZl8';
    $url = $apiUrlStart . urlencode($address) . $apiUrlEnd;


    $response = file_get_contents($url);

    if ($response === false) {
        echo 'Errore nella richiesta HTTP';
    } else {
        $data = json_decode($response, true);
        $positionLatLng = $data['results'][0]['position'][$position];
    }

    return $positionLatLng;
}


}
