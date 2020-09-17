<template>
  <div>
    <p>Enter the name of two towns</p>
    <form @submit.prevent="askWeatherApi">
      <input id="town1" type="text" v-model="town1" name="town1" placeholder="Paris"><br><br>
      <input id="town2" type="text" v-model="town2" name="town2" placeholder="Marseille"><br><br>
      <input type="submit" value="Submit">
    </form>
    <div v-if="response_json">
      <p>Winner: {{ response_json.Winner }}</p>
      <div class="Results">
        <div v-for="city in response_json.Cities">
          <p>Name: {{ city.city_name }}</p>
          <p>Temperature average: {{ city.temp_average }}%</p>
          <p>Cloud average: {{ city.cloud_average }}%</p>
          <p>Humidity average: {{ city.humidity_average }}%</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import qs from 'qs'

export default {
  name: "App",
  data() {
    return {
      response_json: null,
      town1: '',
      town2: ''
    };
  },
  methods: {
    askWeatherApi: function() {
      this.submitForm();
    },
    submitForm: function () {
      axios({
        method: 'post',
        url: '/chooseNextTrip',
        data: qs.stringify({
          town1: this.town1,
          town2: this.town2
        }),
        headers: {
          'content-type': 'application/x-www-form-urlencoded;charset=utf-8'
        }
      })
          .then(response => (this.response_json = response.data))
          .catch(function (error, response) {
            console.log(error);
          });

    }
  }
}
</script>

<style>

</style>