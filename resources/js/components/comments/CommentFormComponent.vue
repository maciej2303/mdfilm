<template>
  <form @submit.prevent="storeComment" id="storeCommentForm">
    <div
      class="comment-time m-b-xs"
      v-if="
        propRelationableType == 'App\\Models\\ProjectElementComponentVersion' &&
        youtubeVideo == true
      "
    >
      <i class="far fa-clock"></i> {{ __('from') }}:
      <div
        class="d-inline-block"
        :class="{ 'has-error': errors && errors.start_time }"
      >
        <input
          type="text"
          class="form-control"
          v-model="start_time_form"
          @click="getTimeFromPlayer(0)"
          @input="setStartTime($event.target.value)"
        />
      </div>
      {{ __('to') }}:
      <div
        class="d-inline-block"
        :class="{ 'has-error': errors && errors.end_time }"
      >
        <input
          type="text"
          class="form-control"
          v-model="end_time_form"
          @click="getTimeFromPlayer(1)"
          @input="setTime(0, $event.target.value)"
        />
      </div>
      <br />
      <small v-if="errors && errors.start_time" class="text-danger"
        >{{ errors.start_time[0] }} <br
      /></small>
      <small v-if="errors && errors.end_time" class="text-danger">{{
        errors.end_time[0]
      }}</small>
    </div>

    <div :class="{ 'has-error': errors && errors.comment }">
      <at
        v-model="form.comment"
        :members="propInner ? innerUsers : users"
        name-key="name"
        @insert="handleInsert"
      >
        <div type="text" class="editor form-control" contenteditable></div>
      </at>
      <small v-if="errors && errors.comment" class="text-danger">{{
        errors.comment[0]
      }}</small>
    </div>
    <div v-if="showInsidePin && user != null &&
        (user.level == 'Admin' || user.level == 'Worker')
      ">
      <input type="checkbox" name-key="inside_pin" v-model="form.inside_pin"> {{ __('inside_comment') }}</input>
    </div>
    <button
      v-if="
        creating == true &&
        propInner == 0 &&
        user != null &&
        (user.level == 'Admin' || user.level == 'Worker')
      "
      type="button"
      @click="showAcceptanceModal(showInsidePin, form.inside_pin)"
      class="btn btn-sm btn-primary m-t-xs m-b add-comment-warning"
    >
      <span v-if="creating">{{ __('add_comment') }}</span>
      <span v-else>{{ __('edit_comment') }}</span>
    </button>
    <button
      v-else
      type="submit"
      class="btn btn-sm btn-primary m-t-xs m-b add-comment-warning"
    >
      <span v-if="creating">{{ __('add_comment') }}</span>
      <span v-else>{{ __('save_comment') }}</span>
    </button>
    <button
      v-if="creating == false"
      @click="turnOffEditing"
      type="button"
      class="btn btn-sm btn-warning m-t-xs m-b add-comment-warning"
    >
      {{ __('cancel') }}
    </button>
  </form>
</template>

<script>
import At from "vue-at";
import timestampToSeconds from "../../mixins/timestampToSecondsMixin.js";
import timestampFromSeconds from "../../mixins/timestampFromSecondsMixin.js";
import { mapGetters } from "vuex";
export default {
  components: { At },
  mixins: [timestampToSeconds, timestampFromSeconds],
  props: [
    "propRelationableId",
    "propRelationableType",
    "youtubeVideo",
    "propInner",
    "modal",
    "showInsidePin"
  ],
  data() {
    return {
      errors: {},
      valid: true,
      creating: true,
      start_time_form: "",
      end_time_form: "",
      form: {
        relationable_id: "",
        relationable_type: "",
        inner: "",
        comment: "",
        userId: "",
        start_time: null,
        end_time: null,
        x: null,
        y: null,
        commentId: null,
        inside_pin: false,
      },
    };
  },
  mounted() {
    this.form.relationable_id = this.propRelationableId;
    this.form.relationable_type = this.propRelationableType;
    this.form.inner = this.propInner;
    this.$eventBus.$on("editComment", this.editComment);
  },
  computed: {
    ...mapGetters({
      users: "users/getUsers",
      innerUsers: "users/getInnerUsers",
      user: "users/getUser",
    }),
  },

  methods: {
    setTime(start_time, value) {
      if (start_time == 1) {
        this.form.start_time = this.timestampToSeconds(value);
      } else {
        this.form.end_time = this.timestampToSeconds(value);
      }
    },
    setStartTime(value) {
      this.form.start_time = this.timestampToSeconds(value);
    },
    editComment(comment) {
      if (comment.inner == this.propInner) {
        this.creating = false;
        this.form.commentId = comment.id;
        this.form.comment = comment.comment;
        this.form.start_time = comment.start_time;
        this.form.end_time = comment.end_time;
        this.start_time_form =
          comment.start_time != null
            ? this.timestampFromSeconds(comment.start_time)
            : "";
        this.end_time_form =
          comment.end_time != null
            ? this.timestampFromSeconds(comment.end_time)
            : "";
      }
    },
    turnOffEditing() {
      this.creating = true;
      this.form.commentId = null;
      this.form.comment = "";
      this.form.start_time = null;
      this.form.end_time = null;
      this.start_time_form = "";
      this.end_time_form = "";
    },
    handleInsert(item) {
      //which user was selected for the future
    },
    passData(data) {
      this.turnOffEditing();
      this.form.x = data.layerX;
      this.form.y = data.layerY;
      this.start_time_form = this.timestampFromSeconds(
        parseInt(data.currentTime)
      );
      this.form.start_time = data.currentTime;
    },
    async getTimeFromPlayer(input) {
      let value = await this.$root.$refs.videoEditorComponent.getCurrentTime();
      if (input == 0 && this.start_time_form == "") {
        this.start_time_form = this.timestampFromSeconds(parseInt(value));
        this.form.start_time = value;
      }
      if (input == 1 && this.end_time_form == "") {
        this.end_time_form = this.timestampFromSeconds(parseInt(value));
        this.form.end_time = value;
      }
    },

    showAcceptanceModal(showInsidePin, inside_pin) {
      if(inside_pin) {
        this.storeComment();
      } else {
        this.$swal({
          title: this.__('add_comment_?'),
          text: this.__('do_you_really_want_to_add_comment_witch_will_be_seen_for_client'),
          showCancelButton: true,
          cancelButtonText: this.__('no'),
          confirmButtonColor: "#1ab394",
          confirmButtonText: this.__('yes'),
          icon: "warning",
          reverseButtons: true,
        }).then((result) => {
          if (result.value) {
            this.storeComment();
          }
        });
      }
    },
    storeComment() {
      this.errors = {};
      this.valid = true;
      if (
        this.start_time_form != "" &&
        !this.start_time_form.match(
          /^(?:(?:[0-1]?[0-9]|2[0-3]):)?([0-5]?[0-9]|2[0-9]):[0-5][0-9]$/
        )
      ) {
        this.valid = false;
        this.errors["start_time"] = [
          this.__('field_time_is_in_wrong_format'),
        ];
      }
      if (
        this.end_time_form != "" &&
        !this.end_time_form.match(
          /^(?:(?:[0-1]?[0-9]|2[0-3]):)?([0-5]?[0-9]|2[0-9]):[0-5][0-9]$/
        )
      ) {
        this.valid = false;
        this.errors["end_time"] = [
          this.__('field_time_is_in_wrong_format'),
        ];
      }
      if (this.valid == true) {
        this.form.userId = this.user != null ? this.user.id : null;
        if (this.creating) {
          axios
            .post("/api/comment/store", this.form)
            .then((response) => {
              this.form.comment = null;
              this.start_time_form = "";
              this.end_time_form = "";
              this.form.start_time = "";
              this.form.end_time = "";
              this.errors = null;
              this.$eventBus.$emit("addComment", response.data);
              if (this.modal == true) {
                this.$parent.close();
                this.$eventBus.$emit("addMarker", response.data);
              }
            })
            .catch((error) => {
              if (error.response.status === 422) {
                this.errors = error.response.data.errors || {};
              }
            });
        } else {
          axios
            .put("/api/comment/update", this.form)
            .then((response) => {
              this.form.comment = null;
              this.start_time_form = "";
              this.end_time_form = "";
              this.errors = null;
              this.creating = true;
              this.$eventBus.$emit("editedComment", response.data);
            })
            .catch((error) => {
              if (error.response.status === 422) {
                this.errors = error.response.data.errors || {};
              }
            });
        }
      }
    },
  },
};
</script>
