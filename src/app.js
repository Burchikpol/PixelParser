import Vue from 'vue'
import axios from 'axios'

Vue.component('app', require('../components/App').default)

let app = new Vue({
    el:"#app"
})