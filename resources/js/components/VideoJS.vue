

<template>
  <div>
    <div class="videojs" style="position: relative">
      <h1>{{ title }}</h1>
      <div class="video-inner-container">
        <video
          id="vid1"
          ref="videoPlayer"
          controls
          preload="auto"
          width="640"
          height="264"
          class="video-js vjs-fluid vjs-default-skin vjs-big-play-centered"
          data-setup="{}"
        >
          <source :src="src" :type="type" autoplay="1" />
        </video>
      </div>
    </div>
    <button
      class="btn btn-pin btn-sm m-t-xs"
      v-if="disabled != true"
      @click="changeButtonStyle"
    >
      <i class="fas fa-thumbtack" aria-hidden="true"></i>
      <span v-if="addPin">{{ __('adding_pin_in_progress') }}</span>
      <span v-else>{{ __('add_pin') }}</span>
    </button>
    <button
      class="btn btn-primary btn-sm m-t-xs"
      v-if="disabled != true && initialUser != null &&
        (initialUser.level == 'Admin' || initialUser.level == 'Worker') && (edlButton == 'inner' || edlButton == 'all')"
      @click="getEdlFile"
    >
      <i class="fas fa-file-download" aria-hidden="true"></i>
      <span>{{ __('export_edl') }}</span>
    </button>
    <button @click="openOnYouTube" class="btn btn-danger btn-sm m-t-xs">
        <i class="fab fa-youtube" aria-hidden="true"></i> {{ __('watch_on_youtube') }}
    </button>
    <button @click="downloadFile(attamchent)" v-if="attamchent.pinned" v-for="attamchent in attachments" class="btn btn-primary btn-sm m-t-xs m-r-xs">
       <i class="fas fa-paperclip" aria-hidden="true"></i> {{ __('download_file') }} ({{attamchent.name}})
    </button>
  </div>
</template>

<script>
import "videojs-youtube/dist/Youtube.min.js";
import "video.js/dist/video-js.min.css";
import "video.js/dist/video.min.js";
import "videojs-markers/dist/videojs-markers.min.js";
import "videojs-markers/dist/videojs.markers.min.css";
import videojs from "video.js";
import MarkerModal from "./MarkerModal.vue";
import timestampFromSeconds from "../mixins/timestampFromSecondsMixin";

export default {
  components: { MarkerModal },
  mixins: [timestampFromSeconds],
  name: "VideoJS",
  props: ["title", "src", "type", "disabled", "model", "initialUser"],
  data() {
    return {
      player: null,
      videoComments: [],
      options: {
        preload: true,
        controls: true,
      },
      addPin: false,
      edlButton: null,
      innerComments: [],
      comments: [],
      attachments: [],
    };
  },
  created() {
    this.$eventBus.$on("getAllComments", this.getComments);
    this.$eventBus.$on("addMarker", this.markerAdded);
    this.$eventBus.$on("removedComment", this.markerRemoved);
    this.$eventBus.$on("editedComment", this.markerUpdated);
    this.$eventBus.$on("modalClosed", this.updateMarkers);
    this.$eventBus.$on("getAttachemts", this.getAttachemts);
  },
  mounted() {
    this.$eventBus.$on("showEdlButton", this.showEdlButton);
    let self = this;
    this.player = videojs(this.$refs.videoPlayer, this.options, function () {
      this.on("firstplay", function () {
        this.volume(0.5);
        self.addMarkers();
      });
      this.on("timeupdate", function () {
        self.updateMarkers();
      });
    });

    this.player.markers({
      markerStyle: {
        width: "5px",
        "border-radius": "30%",
        "background-color": "#f7ec1b",
      },
      markerTip: {
        display: false,
      },
    });
    //adding listener for marker
    let markerArea = document.querySelector(".vjs-text-track-display");
    markerArea.addEventListener("click", (e) => {
      const box = document.querySelector(".videojs");
      let data = (({ layerX, layerY }) => ({
        layerX: (((layerX + 10) / box.offsetWidth) * 100).toFixed(2),
        layerY: (((layerY + 24) / box.offsetHeight) * 100).toFixed(2),
      }))(e);
      if (!e.target.classList.contains("vjs-text-track-display")) {
        data.layerX = data.layerX * 1 + 1.5;
        data.layerY = data.layerY * 1 + 3;
      }
      data = { ...data, currentTime: this.player.currentTime() };
      if (!this.player.paused()) {
        this.player.pause();
      }
      this.$eventBus.$emit("openMarkerModal", data);
    });
  },

  methods: {
    downloadFile(attachment) {
      window.open(attachment.url);
    },
    getAttachemts(attachments) {
      this.attachments = attachments;
    },
    openOnYouTube() {
       window.open(this.src, '_blank');
    },
    getEdlFile() {
      let inner_comments = this.edlButton == 'inner';
      axios
        .post("/api/get/edlcomments", {
          modelId: this.model.id,
          userId: this.initialUser.id,
          inner: inner_comments,
        })
        .then((response) => {
          if(response.data.text == '') {
            this.$swal({
              text: this.__('comments_does_not_have_filled_times_to_do'),
              confirmButtonColor: "#1ab394",
              confirmButtonText: "Ok",
              icon: "warning",
             });
           } else {
             let text = response.data.text.toString().replace(/break/g, '\r\n\r\n')
             let now = new Date();
             let file_name = 'eksport_'+now.getFullYear()+'_'+((now.getMonth()+1) > 9 ? (now.getMonth()+1) : '0'+(now.getMonth()+1))+'_'+now.getDate()+'_'+((now.getHours()) > 9 ? (now.getHours()) : '0'+(now.getHours()))+'_'+((now.getMinutes()) > 9 ? (now.getMinutes()) : '0'+(now.getMinutes()))+'.edl';
             const blob = new Blob([text], {type:'text/plain',endings:'native'})
             const link = document.createElement('a')
             link.href = URL.createObjectURL(blob)
             link.download = file_name;
             link.click()
             URL.revokeObjectURL(link.href)
           }
         });
       
    },
    showEdlButton(val) {
      this.edlButton = val;
    },
    changeButtonStyle() {
      if (this.addPin == false) {
        this.addPin = true;
        document
          .querySelector(".vjs-text-track-display")
          .classList.add("pin-cursor");
        document.querySelector(".vjs-play-control").classList.add("disabled");
      } else {
        this.addPin = false;
        document
          .querySelector(".vjs-text-track-display")
          .classList.remove("pin-cursor");
        document
          .querySelector(".vjs-play-control")
          .classList.remove("disabled");
      }
    },
    markerAdded(data) {
      let comment = data.comment;
      this.addMarker(comment);
      this.changeButtonStyle();
    },
    markerRemoved(commentId) {
      let markers = this.player.markers.getMarkers();
      let index = markers.findIndex((x) => x.id === commentId);
      this.player.markers.remove([index]);
      document
        .querySelectorAll(".marker-" + commentId)
        .forEach((e) => e.remove());
    },
    markerUpdated(data) {
      let markers = this.player.markers.getMarkers();
      let index = markers.findIndex((x) => x.id === data.comment.id);
      if (index != -1) {
        this.player.markers.remove([index]);
        this.addMarker(data.comment);
      }
    },
    addMarker(comment) {
      let displayTime =
        comment.end_time != null ? comment.end_time - comment.start_time : 1;
      this.player.markers.add([
        {
          id: comment.id,
          time: comment.start_time,
          end_time: comment.end_time,
          text: comment.comment,
          displayTime: displayTime,
          x: comment.x,
          y: comment.y,
          class: comment.x == null ? "comment" : "",
        },
      ]);
    },
    updateMarkers() {
      let currentTime = this.player.currentTime();
      let markers = this.player.markers.getMarkers();
      markers.forEach((marker) => {
        document
          .querySelectorAll(".marker-" + marker.id)
          .forEach((e) => e.remove());

        if (
          currentTime >= marker.time &&
          currentTime <= marker.time + marker.displayTime &&
          marker.x != null &&
          marker.time != null
        ) {
          let screen = document.querySelector(".videojs");
          let markerElement =
            `<div data-id="` +
            marker.id +
            `" class="marker marker-` +
            marker.id +
            ` "style="cursor: default; text-align: center; cursor: pointer; position:absolute; transform: translate(-50%, -100%); top: ` +
            marker.y +
            `%; left: ` +
            marker.x +
            `%"><div class="well" style="max-width: 500px; margin-top: 0px;"><span><i aria-hidden="true" class="fas fa-thumbtack"></i> <i class="far fa-clock"></i> <span>{{ __('from') }}:
            <strong><a>` +
            this.timestampFromSeconds(marker.time) +
            `</a></strong></span> <span>`;
          let to = "";
          if (marker.end_time != null) {
            to +=
              "{{ __('to') }}: <strong><a>" +
              this.timestampFromSeconds(marker.end_time) +
              `</a></strong>`;
          }
          markerElement +=
            to +
            `</span> <br></span> <div><span>` +
            marker.text +
            `</span></div></span></div><span><i
              class="fas fa-thumbtack marker-icon"
              aria-hidden="true"
            ></i></span></div>`;

          screen.insertAdjacentHTML("beforeend", markerElement);
          let addedMarker = document.querySelector(".marker-" + marker.id);
          addedMarker.addEventListener("click", (e) => {
            document
              .querySelector("#comment-" + marker.id)
              .scrollIntoView({ behavior: "smooth" });
          });
        }
      });
    },
    seekToTime(time) {
      this.player.currentTime(time);
    },
    getCurrentTime() {
      return this.player.currentTime();
    },
    hasStarted() {
      return this.player.hasStarted();
    },
    stringToHTML(str) {
      var parser = new DOMParser();
      var doc = parser.parseFromString(str, "text/html");
      return doc.body;
    },
    async playVideo() {
      return this.player.play();
    },
    getComments(comments) {
      this.videoComments = comments;
    },

    addMarkers() {
      this.videoComments.forEach((comment) => {
        if (comment.start_time != null) {
          let displayTime =
            comment.end_time != null
              ? comment.end_time - comment.start_time
              : 1;
          this.player.markers.add([
            {
              id: comment.id,
              time: comment.start_time,
              end_time: comment.end_time,
              text: comment.comment,
              displayTime: displayTime,
              x: comment.x,
              y: comment.y,
              class: comment.x == null ? "comment" : "",
            },
          ]);
        }
      });
    },
  },
};
</script>

<style>
.video-js .vjs-time-control {
  display: block;
}

.video-js.vjs-user-inactive.vjs-playing .vjs-text-track-display {
  bottom: 3em !important;
}
.video-js .vjs-remaining-time {
  display: none;
}
.vjs-marker {
  display: block !important;
}
.vjs-marker.comment {
  background-color: red !important;
}
.marker {
  font-size: 20px;
}
.well {
  border: 1px solid #e7eaec;
  background: #ffffff;
  box-shadow: none;
  margin-top: 10px;
  margin-bottom: 5px;
  padding: 10px 20px;
  font-size: 11px;
  line-height: 16px;
  font-family: "open sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
  color: #676a6c;
}

.pin {
  border-radius: 11.2px 11.2px 11.2px 0;
  width: 9.6px;
  height: 9.6px;
  background-color: #38ce16 !important;
  border-color: #38ce16 !important;
  display: inline-block;
  position: relative;
  -webkit-transform: rotate(-45deg);
  transform: rotate(-45deg);
  border-width: 4.8px;
  border-style: solid;
  -webkit-box-sizing: content-box;
  box-sizing: content-box;
}
.btn-pin {
  background-color: rgb(237, 85, 101);
  color: white;
}
.btn-pin:hover {
  background-color: rgb(242, 58, 76);
  color: white;
}
.marker-icon {
  color: rgb(237, 85, 101);
}
.pin-cursor {
  cursor: url("/img/marker.svg"), auto;
  pointer-events: all;
}
</style>