// window._ = require('lodash');
// window.dayjs = require('dayjs');
// window.$ = require('jquery');

import _ from 'lodash';
window._ = _;

import dayjs from 'dayjs';
window.dayjs = dayjs;

import jquery from 'jquery';
window.$ = window.jQuery = jquery;

import axios from 'axios';
window.axios = axios;

// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
