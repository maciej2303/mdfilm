<template>
  <div class="feed-activity-list">
    <div
      class="feed-element"
      :id="`comment-${comment.id}`"
      v-for="comment in comments"
      :key="comment.id"
    >
      <div class="media-body">
        <div class="well-header m-t-xs m-b-xs">
          <small class="float-right"
            >{{ comment.created_at | formatDate }}
            <span v-if="comment.updated_at != comment.created_at">
              ({{ __('last_edit') }}: {{ comment.updated_at | formatDate }})</span
            >
            <a
              class="btn btn-default btn-xs btn-micro tooltip-more"
              :title="__('add_to_do_position')"
              data-toggle="modal"
              data-target="#addTodo"
              data-placement="left"
              :data-content="toDoComment(comment)"
              v-if="user != null && user.level != 'Client'"
              ><i class="fas fa-check-square"></i> {{ __('to_do') }}</a
            >
            <a
              class="btn btn-default btn-xs btn-micro tooltip-more"
              :title="__('edit_comment')"
              @click="editComment(comment)"
              v-if="user != null && comment.authorable_id == user.id"
              ><i class="fas fa-pen"></i> {{ __('edit') }}</a
            >
            <button
              class="btn btn-danger btn-xs btn-micro tooltip-more"
              :title="__('delete_comment')"
              v-if="user != null && user.level == 'Admin'"
              @click="showDeleteModal(comment.id)"
            >
              <i class="fas fa-trash"></i> {{ __('delete') }}
            </button>
          </small>
          <span
            class="label label-danger"
            v-if="user != null && comment.new !== undefined && comment.new == 1"
            >{{ __('new') }}</span
          >
          <strong>{{ comment.authorable.name }}</strong>
          <span class="label label-default" v-if="comment.label == 'Klient'"
            >{{ __('client') }}</span
          >
          <span class="label label-default" v-if="comment.label == 'Bez konta'"
            >{{ __('no_account') }}</span
          >
          {{ __('wrote') }}:
        </div>
        <div class="well">
          <span v-if="comment.start_time != null || comment.end_time != null"
            ><i
              v-if="comment.x != null"
              class="fas fa-thumbtack marker-icon"
              aria-hidden="true"
              @click="seekToYTPlayer(comment.start_time)"
            ></i>
            <i class="far fa-clock"></i>
            <span v-if="comment.start_time != null"
              >{{ __('from') }}:
              <strong
                ><a @click="seekToYTPlayer(comment.start_time)">{{
                  comment.start_time | formatYoutubeTime
                }}</a></strong
              >
            </span>
            <span v-if="comment.end_time != null">
              {{ __('to') }}:
              <strong
                ><a @click="seekToYTPlayer(comment.end_time)">{{
                  comment.end_time | formatYoutubeTime
                }}</a></strong
              >
            </span>
            <br />
          </span>
          <comment-box-component
            :initial-comment="comment.comment"
          ></comment-box-component>
        </div>
      </div>
    </div>
    <span v-if="comments.length === 0">{{ __('no_comments') }}</span>
  </div>
</template>

<script>
import highlightUser from "../../mixins/highlightUserMixin.js";
import timestampToSeconds from "../../mixins/timestampToSecondsMixin.js";
import timestampFromSeconds from "../../mixins/timestampFromSecondsMixin.js";
import { mapGetters } from "vuex";
export default {
  name: "CommentsList",
  mixins: [highlightUser, timestampToSeconds, timestampFromSeconds],
  props: {
    propComments: { type: Array, default: () => [] },
  },
  data() {
    return {
      comments: [],
    };
  },
  watch: {
    propComments: {
      deep: true,
      handler(value, oldValue) {
        this.comments = value;
      },
    },
  },
  computed: {
    ...mapGetters({
      user: "users/getUser",
    }),
  },
  updated() {
    $(".tooltip-more").tooltip();
  },
  methods: {
    seekToYTPlayer(seconds) {
      this.$eventBus.$emit("seekTo", seconds);
    },
    editComment(comment) {
      this.$eventBus.$emit("editComment", comment);
      document
        .getElementById("tabs-panel-body")
        .scrollIntoView({ behavior: "smooth" });
    },
    toDoComment(comment) {
      let content = "";
      if (comment.start_time != null) {
        content = this.__('from')+": " + this.timestampFromSeconds(comment.start_time);
        if (comment.end_time != null) {
          content =
            content + " "+this.__('to')+" " + this.timestampFromSeconds(comment.end_time);
        }
        content += " | ";
      } else {
        if (comment.end_time != null) {
          content += this.__('to')+": " + this.timestampFromSeconds(comment.end_time);
          content += " | ";
        }
      }
      return content + comment.comment;
    },
    showDeleteModal(commentId) {
      this.$swal({
        title: this.__('delete_?'),
        text: this.__('element_wont_be_recoverable_!'),
        showCancelButton: true,
        cancelButtonText: this.__('do_not_delete'),
        confirmButtonColor: "#dd6b55",
        confirmButtonText: this.__('delete'),
        icon: "warning",
        reverseButtons: true,
      }).then((result) => {
        if (result.value) {
          this.deleteComment(commentId);
        }
      });
    },
    deleteComment(commentId) {
      axios
        .post("/api/comment/delete", {
          commentId: commentId,
        })
        .then((response) => {
          this.comments = this.comments.filter((x) => {
            return x.id != commentId;
          });
          this.$eventBus.$emit("removedComment", commentId);
        })
        .catch((error) => {});
    },
  },
};
</script>

<style>
.marker-icon {
    color: rgb(237, 85, 101);
}
.marker-icon:hover {
    cursor: pointer;
}
</style>