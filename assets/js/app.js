/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
import Vue from 'vue';
import App from './components/App';
import '../css/app.css';

new Vue({
    el: '#app',
    components: { App },
    template: "<App/>",
    render: h => h(App)
})