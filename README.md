# ChooseNextTrip
A little headless Symfony web app which can compare two citie's weather conditions.

# How to use it:
First [install symfony](https://symfony.com/doc/4.2/setup.html#installing-symfony). Then get API keys for the [google geocode API] (https://developers.google.com/maps/documentation/geocoding/start) and for the [OpenWeatherMap onecall API](https://openweathermap.org/api/one-call-api?gclid=EAIaIQobChMIoOXN1Mqa6wIVBZ3VCh0GYwtDEAAYASAAEgJfP_D_BwE).
Then, clone the repository and run the following command: `$cd ChooseNextTrip && symfony composer install`
Now that you have installed the dependencies run `$symfony serve` into the repository, the server is now running.
To submit your parameters use a tool to simulate and http POST request (postman for example).
In the body of your request include town1 and town2 parameters like `town1={example}&town2={example}` and make sure the content-type in the header is `application/x-www-form-urlencoded`.
The app will return a JSON describing the weather for the next week for the city which has the nicest weather.

The best weather parameters are :
- 27°c
- 60% humidity
- 15% cloudiness
