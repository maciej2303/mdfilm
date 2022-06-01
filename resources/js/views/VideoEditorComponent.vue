<template>
  <div class="">
    <div class="tabs-container general-container m-b">
      <ul class="nav nav-tabs" role="tablist">
        <li v-for="version in versions" :key="version.id">
          <template v-if="version.inner == 1">
            <a
              v-if="user != null && user.level != 'Client'"
              class="nav-link"
              :class="{ active: version.id === projectVersion.id }"
              :href="goto_route_show(version.id, version.inner)"
            >
              {{ version.version }}
              <span class="label label-default">{{ __('inside') }}</span>
            </a>
          </template>
          <template v-else>
            <a
              class="nav-link"
              :class="{ active: version.id === projectVersion.id }"
              :href="goto_route_show(version.id, version.inner)"
            >
              {{ version.version }}
            </a>
          </template>
        </li>
      </ul>
      <div class="tab-content">
        <div role="tabpanel" id="film-1" class="tab-pane active">
          <div class="panel-body">
            <video-js
              ref="player"
              :src="
                'https://www.youtube.com/watch?v=' + projectVersion.youtube_url
              "
              :disabled="disabled"
              :initial-user="user"
              :model="projectVersion"
              type="video/youtube"
            ></video-js>
            <marker-modal
              v-if="disabled == false"
              :prop-relationable-id="projectVersion.id"
              :prop-relationable-type="projectVersionClass"
              :prop-inner="0"
            ></marker-modal>
          </div>
        </div>
      </div>
    </div>
    <acceptance-box-component
      :acceptances="projectVersion.acceptances"
      :initial-user="user"
      :avaliable-acceptance="avaliableAcceptance"
      :project-version="projectVersion"
    />
    <tabs-panel-component
      :is-project-show="false"
      :model="projectVersion"
      :model-class="projectVersionClass"
      :initial-user="user"
      :disabled="disabled"
      :project-id="project.id"
    />
  </div>
</template>

<script>
import AcceptanceBoxComponent from "../components/AcceptanceBoxComponent";
import TabsPanelComponent from "../components/TabsPanelComponent";
import Vuex from "vuex";
import VueYoutube from "vue-youtube";
import VideoJS from "../components/VideoJS";

Vue.use(Vuex);
Vue.use(VueYoutube);

export default {
  components: {
    AcceptanceBoxComponent,
    TabsPanelComponent,
    VideoJS,
  },
  props: [
    "projectVersion",
    "project",
    "versions",
    "showRoute",
    "user",
    "avaliableAcceptance",
    "projectVersionClass",
    "disabled",
  ],

  data() {
    return {
      users: [],
    };
  },

  mounted() {
    this.videoId = this.projectVersion.youtube_url;
    this.$eventBus.$on("seekTo", this.youtubeSeekTo);
  },

  async created() {
    await this.getUsers();
  },
  methods: {
    goto_route_show: function (id) {
      return `${this.showRoute}/${id}/`;
    },
    async youtubeSeekTo(sec) {
      let hasStarted = await this.$refs.player.hasStarted();
      this.$refs.player.updateMarkers();
      if (hasStarted == false) {
        await this.$refs.player.playVideo();
        setTimeout(async () => {
          await this.$refs.player.playVideo();
        }, 1000);
        setTimeout(() => {
          this.$refs.player.seekToTime(sec);
        }, 500);
      } else {
        this.$refs.player.seekToTime(sec);
      }
      this.$el.scrollIntoView({ behavior: "smooth" });
    },
    async getCurrentTime() {
      return this.$refs.player.getCurrentTime();
    },
    async getUsers() {
      await axios
        .post("/api/get/users", {
          modelId: this.projectVersion.id,
          modelClass: this.projectVersionClass,
        })
        .then((response) => {
          this.users = response.data.users;
        });
    },
  },
};
</script>
<style>
.ytp-pause-overlay {
  display: none;
}
</style>