<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController
{
    public $latitude;
    public $longitude;
    public $json;
    public $city_name;
    public $temp_average = 0;
    public $humidity_average = 0;
    public $cloud_average = 0;
    public $points = 0;

    public function getLocation(string $town)                                   /* Transform town name into geographic coordinates */
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$town&key=API_KEY";   /* Query string to Google geocode API */
        $json = file_get_contents($url);
        $decoded_json = json_decode($json);
        $this->latitude = $decoded_json->results[0]->geometry->location->lat;
        $this->longitude = $decoded_json->results[0]->geometry->location->lng;
        $this->city_name = $town;                                               /* store town name with coordinates */
    }

    public function getWeather()
    {
        $count = 0;
        $url = "https://api.openweathermap.org/data/2.5/onecall?lat=$this->latitude&lon=$this->longitude&units=metric&exclude=current,minutely,hourly&appid=API_KEY"; /* Query string to OpenWeatherMap OneCall API */
        $json = file_get_contents($url);
        $decoded_json = json_decode($json);
        foreach ($decoded_json->daily as $day) {                                /* Get the temperature, humidity and cloud average for the next week */
            $this->temp_average += $day->temp->max;
            $this->humidity_average += $day->humidity;
            $this->cloud_average += $day->clouds;
            $count++;
        }
        $this->temp_average /= $count;
        $this->humidity_average /= $count;
        $this->cloud_average /= $count;
        $decoded_json = json_decode($json, true);
        $decoded_json['City'] = $this->city_name;                               /* Add city name to the weather informations */
        $this->json = json_encode($decoded_json);
    }
}

class WeatherController
{
    private function calculate_difference(int $val, int $ref)                   /* calculate the difference between a value and a reference */
    {                                                                           /* and make sure the offset is positive so it can be compared */
        $diff = $val - $ref;
        if ($diff < 0)
            $diff *= -1;
        return ($diff);
    }

    private function addPoints(LocationController $location1, LocationController $location2)
    {
        $temp_diff1 = $this->calculate_difference($location1->temp_average, (int)27);
        $temp_diff2 = $this->calculate_difference($location2->temp_average, (int)27);
        $cloud_diff1 = $this->calculate_difference($location1->cloud_average, (int)15);
        $cloud_diff2 = $this->calculate_difference($location2->cloud_average, (int)15);
        $humidity_diff1 = $this->calculate_difference($location1->humidity_average, (int)60);
        $humidity_diff2 = $this->calculate_difference($location2->humidity_average, (int)60);

        if ($temp_diff1 <= $temp_diff2)                                         /* add points for temperature */
            $location1->points += 20;
        else
            $location2->points += 20;
        if ($humidity_diff1 <= $humidity_diff2)                                 /* add points for humidity */
            $location1->points += 15;
        else
            $location2->points += 15;
        if ($cloud_diff1 <= $cloud_diff2)                                       /* add points for cloud percentage */
            $location1->points += 10;
        else
            $location2->points += 10;
    }

    public function compareWeather(Request $request)
    {
        $town1 = $request->request->get('town1');
        $town2 = $request->request->get('town2');
        $location1 = new LocationController;
        $location2 = new LocationController;

        $location1->getLocation($town1);
        $location2->getLocation($town2);
        $location1->getWeather();
        $location2->getWeather();
        $this->addPoints($location1, $location2);
        if ($location1->points > $location2->points) {                          /* build response with the best city's weather JSON */
            $response = new Response($location1->json);
        } else if ($location1->points <= $location2->points) {
            $response = new Response($location2->json);
        }
        $response->headers->set('Content-Type', 'application/json');            /* set header content-type field value */
        return $response;
    }
}
