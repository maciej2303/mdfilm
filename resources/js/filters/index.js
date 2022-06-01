import Vue from "vue";
import formatYoutubeTime from "./formatYoutubeTime"
import formatDate from "./formatDate"

Vue.filter("formatYoutubeTime", formatYoutubeTime);
Vue.filter("formatDate", formatDate);