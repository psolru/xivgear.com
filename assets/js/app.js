"use strict";

import "bootstrap";
import Vue from 'vue';
import MainFayt from './components/MainFayt'
require("../css/app.scss");

new Vue({
    el: '#app',
    components: {MainFayt}
});