<template>
  <div class="Main">
    <div class="Main-Header">
      <h1>Compare the Weather in different towns</h1>
    </div>
    <div class="Main-Content">
      <div class="Form">
        <div>
          <p>Enter the name of two towns</p>
          <form @submit.prevent="submitForm" class="Form">
            <input id="town1" type="text" v-model="town1" name="town1" placeholder="Paris"><br><br>
            <input id="town2" type="text" v-model="town2" name="town2" placeholder="Marseille"><br><br>
            <input type="submit" value="Compare !">
          </form>
        </div>
      </div>
      <div v-if="error_msg" class="Main-Error">
        <p>{{ error_msg }}</p>
      </div>
      <div v-if="response_json" class="Result-Main">
        <p class="Winner">Looks like the weather will be nicer in {{ response_json.Winner }} next week !</p>
        <div class="Results-City">
          <div v-for="city in response_json.Cities">
            <p><strong>City:</strong> {{ city.city_name }}</p>
            <p><strong>Temperature average:</strong> {{ city.temp_average }}%</p>
            <p><strong>Cloud average:</strong> {{ city.cloud_average }}%</p>
            <p><strong>Humidity average:</strong> {{ city.humidity_average }}%</p>
          </div>
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
      town2: '',
      error_msg: null
    };
  },
  methods: {
    sanitizeData: function (data) {
      if (data.Status == "Error") {
        this.error_msg = "Invalid parameter, no result";
      } else {
        this.response_json = data;
      }
    },
    submitForm: function () {
      this.response_json = null;
      this.error_msg = null;
      if (this.town1 && this.town2) {
        axios({
          method: 'post',
          url: '/chooseNextTrip',
          data: qs.stringify({
            town1: this.town1,
            town2: this.town2,
          }),
          headers: {
            'content-type': 'application/x-www-form-urlencoded;charset=utf-8'
          }
        })
            .then(response => (this.sanitizeData(response.data)))
            .catch(function (error, response) {
              console.log(error);
            });
      } else {
        this.error_msg = 'Missing parameter: you have to enter the name of two towns'
      }
    }
  }
}
</script>

<style>

</style>