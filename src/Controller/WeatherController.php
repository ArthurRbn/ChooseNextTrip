<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController
{
    public $latitude;
    public $longitude;
    public $temp_average = 0;
    public $humidity_average = 0;
    public $cloud_average = 0;
    public $points = 0;

    public function getLocation(string $town)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$town&key=APIKEY";
        $json = file_get_contents($url);
        $json = json_decode($json);
        $this->latitude = $json->results[0]->geometry->location->lat;
        $this->longitude = $json->results[0]->geometry->location->lng;
    }

    public function getWeather()
    {
        $count = 0;
        $url = "https://api.openweathermap.org/data/2.5/onecall?lat=$this->latitude&lon=$this->longitude&units=metric&exclude=current,minutely,hourly&appid=APIKEY";
        $json = file_get_contents($url);
        $json = json_decode($json);
        foreach ($json->daily as $day) {
            $this->temp_average += $day->temp->max;
            $this->humidity_average += $day->humidity;
            $this->cloud_average += $day->clouds;
            $count++;
        }
        $this->temp_average /= $count;
        $this->humidity_average /= $count;
        $this->cloud_average /= $count;
    }
}

class WeatherController
{
    private function calculate_difference(int $val, int $ref)
    {
        $diff = $val - $ref;
        if ($diff < 0)
            $diff *= -1;
        return ($diff);
    }

    private function addPoints(LocationController $location1, LocationController $location2)
    {
        $temp_diff1 = $this->calculate_difference($location1->temp_average, (int)27);
        $temp_diff2 = $this->calculate_difference($location2->temp_average, (int)27);
        $cloud_diff1 = $this->calculate_difference($location1->temp_average, (int)15);
        $cloud_diff2 = $this->calculate_difference($location2->temp_average, (int)15);
        $humidity_diff1 = $this->calculate_difference($location1->temp_average, (int)60);
        $humidity_diff2 = $this->calculate_difference($location2->temp_average, (int)60);

        if ($temp_diff1 <= $temp_diff2)
            $location1->points += 20;
        else
            $location2->points += 20;
        if ($humidity_diff1 <= $humidity_diff2)
            $location1->points += 15;
        else
            $location2->points += 15;
        if ($cloud_diff1 <= $cloud_diff2)
            $location1->points += 20;
        else
            $location2->points += 20;
    }

    public function compareWeather(string $town1, string $town2)
    {
        $location1 = new LocationController;
        $location2 = new LocationController;

        $location1->getLocation($town1);
        $location2->getLocation($town2);
        $location1->getWeather();
        $location2->getWeather();
        $this->addPoints($location1, $location2);
        if ($location1->points > $location2->points) {
            return new Response(
                '<html><body>Location '.$town1.' will have a nicer weather than '.$town2.' next week</body></html>'
            );
        }
        if ($location1->points <= $location2->points) {
            return new Response(
                '<html><body>Location '.$town2.' will have a nicer weather than '.$town1.' next week</body></html>'
            );
        }
    }
}