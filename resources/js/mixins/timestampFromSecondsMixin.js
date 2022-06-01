

export default {
  methods: {
    timestampFromSeconds(value) {
      let hours = Math.floor(value / 60 / 60);
      let minutes = Math.floor(value / 60) - hours * 60;
      let seconds = value % 60;
      seconds = Math.floor(seconds);
      if (hours > 0 && minutes < 10) minutes = "0" + minutes;
      if (seconds < 10) seconds = "0" + seconds;
      let time = minutes + ":" + seconds;
      if (hours > 0) time = hours + ":" + time;
      return time;
    },
  }
};