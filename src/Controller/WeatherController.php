<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class locationController
{
    public $location_x;
    public $location_y;

    public function getLocation(string $town)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$town."&key=";
        $session = curl_init($url);
        $content = null;

        curl_setopt($session, CURLOPT_HEADER, 0);
        $content = curl_exec($session);
        if (curl_error($session)) {
            $this->location_x = 0;
            $this->location_y = 0;
        } else {
            /*
            * TODO: fetch coord from JSON and return them
            */
        }
        curl_close($session);
    }
}

class WeatherController
{
    public function compareWeather(string $town1, string $town2)
    {
        $location1 = new locationController;
        $location2 = new locationController;

        $location1->getLocation($town1);
        $location2->getLocation($town2);
        /*
        * TODO: API call with coords, extract weather data from JSON, sort town by weather
        */
        return new Response(
            '<html><body>Lucky number: coucou'.$town1.$town2.'</body></html>'
        );
    }
}
