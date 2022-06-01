<template>
  <!-- ZAKŁADKI -->
  <div class="tabs-container">
    <div class="tabs-left">
      <ul class="nav nav-tabs">
        <li>
          <a
            v-if="showComments"
            class="nav-link active"
            data-toggle="tab"
            href="#tab-1"
            @click="resetInnerCommentsCount()"
            >{{ __('comments') }}
            <span
              class="label label-danger"
              v-if="commentsCount > 0 && user != null"
              >{{ commentsCount }}</span
            ></a
          >
        </li>
        <li>
          <a
            v-if="user != null && user.level != 'Client' && showComments"
            class="nav-link"
            data-toggle="tab"
            href="#tab-2"
            @click="setInnerComments()"
            >{{ __('inside_comments') }}
            <span
              class="label label-danger"
              v-if="innerCommentsCount > 0 && user != null"
              >{{ innerCommentsCount }}</span
            ></a
          >
        </li>
        <li>
          <a
            class="nav-link"
            v-bind:class="{ 'active' : !showComments } "
            data-toggle="tab"
            href="#tab-3"
            @click="resetAllCommentsCount"
            >{{ __('attachments') }}</a
          >
        </li>
        <li>
          <a
            class="nav-link"
            data-toggle="tab"
            href="#tab-4"
            @click="resetAllCommentsCount()"
            v-if="
              (modelClass == 'App\\Models\\Project' ||
                (modelClass == 'App\\Models\\ProjectElementComponentVersion' &&
                  model.simple == 1)) &&
              user != null &&
              user.level == 'Admin'
            "
            >{{ __('add_work_time') }}</a
          >
        </li>
      </ul>
      <div class="tab-content">
        <!-- KOMENTARZE -->
        <div id="tab-1" class="tab-pane" v-bind:class="{ 'active' : showComments } ">
          <div class="panel-body" id="tabs-panel-body">
            <comment-form-component
              v-if="disabled == false"
              :prop-relationable-id="model.id"
              :prop-relationable-type="modelClass"
              :prop-inner="0"
              :show_inside_pin="false"
              :prop-user-id="userId"
              :user="user"
              :youtube-video="youtubeVideo"
              :modal="false"
            />
            <comments-list-component :prop-comments="comments" />
          </div>
        </div>
        <!-- /KOMENTARZE -->

        <!-- KOMENTARZE WEWNĘTRZNE-->
        <div role="tabpanel" id="tab-2" class="tab-pane">
          <div class="panel-body">
            <comment-form-component
              v-if="disabled == false"
              :show_inside_pin="false"
              :prop-relationable-id="model.id"
              :prop-relationable-type="modelClass"
              :prop-inner="1"
              :prop-user-id="userId"
              :user="user"
              :youtube-video="youtubeVideo"
              :modal="false"
            />
            <comments-list-component :prop-comments="innerComments" />
          </div>
        </div>
        <!-- /KOMENTARZE WEWNĘTRZNE-->
        <!-- ZAŁĄCZNIKI-->
        <div role="tabpanel" id="tab-3" class="tab-pane" v-bind:class="{ 'active' : !showComments } ">
          <div class="panel-body">
            <attachment-form-component
              v-if="disabled == false"
              :prop-relationable-id="model.id"
              :prop-relationable-type="modelClass"
              :user-id="userId"
              @attachment="addedNewAttachment"
            />
            <attachments-list-component
              :prop-attachments="attachments"
              :video="youtubeVideo"
              :user="user"
            />
          </div>
        </div>
        <!-- /ZAŁĄCZNIKI-->
        <!-- CZAS PRACY -->
        <div
          role="tabpanel"
          id="tab-4"
          class="tab-pane"
          v-if="
            (modelClass == 'App\\Models\\Project' ||
              (modelClass == 'App\\Models\\ProjectElementComponentVersion' &&
                model.simple == 1)) &&
            user != null &&
            user.level == 'Admin'
          "
        >
          <work-time-tab-component :project-id="projectId" />
        </div>
      </div>
    </div>
  </div>
  <!-- /ZAKŁADKI -->
</template>

<script>
import axios from "axios";
import CommentFormComponent from "./comments/CommentFormComponent.vue";
import CommentsListComponent from "./comments/CommentsListComponent.vue";
import WorkTimeTabComponent from "./WorkTimeTabComponent.vue";
import AttachmentFormComponent from "./attachments/AttachmentFormComponent.vue";
import AttachmentsListComponent from "./attachments/AttachmentsListComponent.vue";
import { mapActions, mapGetters } from "vuex";

export default {
  components: {
    CommentFormComponent,
    CommentsListComponent,
    WorkTimeTabComponent,
    AttachmentFormComponent,
    AttachmentsListComponent,
  },
  name: "TabsPanel",
  props: ["model", "modelClass", "initialUser", "disabled", "projectId", "isProjectShow"],
  data() {
    return {
      showEdl: 'all',
      attachments: null,
      comments: [],
      videoComments: [],
      innerComments: [],
      commentsCount: null,
      innerCommentsCount: null,
      fields: {},
      errors: {},
      userId: null,
      youtubeVideo: false,
      innerCommentsFirstTime: true,
      users: [],
      innerUsers: [],
    };
  },
  mounted() {
    this.$eventBus.$on("addComment", this.addedNewComments);
    this.$eventBus.$on("editedComment", this.editedComment);
    this.$eventBus.$on("removedComment", this.removedComment);
  },
  computed: {
    ...mapGetters({
      user: "users/getUser",
    }),
    showComments() {
        return ((this.innerCommentsCount > 0 || this.commentsCount > 0) || !this.isProjectShow );
    }
  },
  methods: {
    addedNewAttachment(response) {
      this.attachments.unshift(response);
    },

    addedNewComments(response) {
      if (response.inner == 0) {
        this.comments.unshift(response.comment);
      } else {
        this.innerComments.unshift(response.comment);
      }
    },
    removedComment(commentId) {
      this.comments = this.comments.filter((x) => {
        return x.id != commentId;
      });
      this.innerComments = this.innerComments.filter((x) => {
        return x.id != commentId;
      });
    },
    editedComment(response) {
      let index = 0;
      if (response.inner == 0) {
        index = this.comments.findIndex((x) => x.id == response.comment.id);
        this.$set(this.comments, index, response.comment);
      } else {
        index = this.innerComments.findIndex(
          (x) => x.id == response.comment.id
        );
        this.$set(this.innerComments, index, response.comment);
      }
    },

    async getAttachments() {
      await axios
        .post("/api/get/attachments", {
          modelId: this.model.id,
          modelClass: this.modelClass,
          userId: this.userId,
        })
        .then((response) => {
          this.attachments = response.data.attachments;
        });
    },
    async getAllComments() {
      await axios
        .post("/api/get/comments", {
          modelId: this.model.id,
          modelClass: this.modelClass,
          userId: this.userId,
          forVideoView: true,
          inner: 0,
        })
        .then((response) => {
          this.videoComments = response.data.comments;
        });
    },
    async getComments() {
      await axios
        .post("/api/get/comments", {
          modelId: this.model.id,
          modelClass: this.modelClass,
          userId: this.userId,
          inner: 0,
        })
        .then((response) => {
          this.comments = response.data.comments;
          this.commentsCount = response.data.commentsCount;
          this.innerCommentsCount = response.data.innerCommentsCount;
        });
    },
    async getInnerComments() {
      await axios
        .post("/api/get/comments", {
          modelId: this.model.id,
          modelClass: this.modelClass,
          userId: this.userId,
          inner: 1,
        })
        .then((response) => {
          this.innerComments = response.data.comments;
          this.innerCommentsCount = response.data.commentsCount;
        });
    },

    resetCommentsCount() {
      this.commentsCount = 0;
      this.comments.forEach((element) => (element.new = 0));
    },

    resetInnerCommentsCount() {
      if (this.innerCommentsFirstTime == false) {
        this.innerCommentsCount = 0;
        this.innerComments.forEach((element) => (element.new = 0));
      }
      this.showEdl = 'all';
      this.$eventBus.$emit("showEdlButton", this.showEdl);
    },
    setInnerComments() {
      if (this.innerCommentsFirstTime == true) {
        this.resetCommentsCount();
        this.getInnerComments();
        this.innerCommentsFirstTime = false;
      }
      this.showEdl = 'inner';
      this.$eventBus.$emit("showEdlButton", this.showEdl);
    },
    resetAllCommentsCount() {
      this.resetCommentsCount();
      this.resetInnerCommentsCount();
      this.showEdl = false;
      this.$eventBus.$emit("showEdlButton", this.showEdl);
    },
    ...mapActions({
      fetchUsers: "users/fetchUsers",
      initUser: "users/initUser",
    }),
  },
  async created() {
    if (this.model.youtube_url != undefined && this.model.youtube_url != "")
      this.youtubeVideo = true;
    await this.fetchUsers({
      modelId: this.model.id,
      modelClass: this.modelClass,
    });
    this.$eventBus.$emit("getComments", this.comments);
    this.$eventBus.$emit("showEdlButton", this.showEdl);
    this.initUser(this.initialUser);
    this.userId = this.user != null ? this.user.id : null;
    await this.getComments();
    await this.getAllComments();
    await this.getAttachments();
    this.$eventBus.$emit("getAllComments", this.videoComments);
  },
};
</script>
