<template>
  <div>
    <component v-bind:is="processedHtml(comment)"></component>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import highlightUser from "../../mixins/highlightUserMixin.js";
import timestampToSeconds from "../../mixins/timestampToSecondsMixin.js";
export default {
  name: "CommentsList",
  mixins: [highlightUser, timestampToSeconds],
  props: {
    initialComment: { type: String, default: "" },
  },
  data() {
    return {
      comment: "",
    };
  },
  computed: {
    ...mapGetters({
      users: "users/getUsers",
    }),
  },
  watch: {
    initialComment: {
      deep: true,
      handler(value, oldValue) {
        this.comment = value;
      },
    },
  },
  mounted() {
    this.comment = this.initialComment;
  },
  methods: {
    processedHtml(comment) {
      let html = this.timestamp(comment);
      html = this.urlify(html);
      return {
        template: "<span>" + html + "</span>",
        methods: {
          seekToYTPlayer(seconds) {
            this.$eventBus.$emit("seekTo", seconds);
          },
        },
      };
    },
    timestamp(text) {
      let self = this;
      var timeRegex =
        /(?:(?:[0-1]?[0-9]|2[0-3]):)?([0-5]?[0-9]|2[0-9]):[0-5][0-9]/gm;
      return text.replace(timeRegex, function (time) {
        return (
          `<strong><a @click="seekToYTPlayer(` +
          self.timestampToSeconds(time) +
          ')">' +
          time +
          "</a></strong>"
        );
      });
    },
    urlify(text) {
      text = this.highlightUser(text);
      var urlRegex = /(https?:\/\/[^\s]+)/g;
      return text.replace(urlRegex, function (url) {
        return '<a href="' + url + '" target="_blank">' + url + "</a>";
      });
    },
  },
};
</script>
