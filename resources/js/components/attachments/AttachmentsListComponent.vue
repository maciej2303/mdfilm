<template>
  <div>
    <div
      class="feed-element"
      v-for="attachment in attachments"
      :key="attachment.id"
    >
      <div class="well-header m-t-xs m-b-xs">
        <small class="float-right">{{
          attachment.created_at | formatDate
        }} 
          <span v-if="video && user != null &&
            (user.level == 'Admin' || user.level == 'Worker')">
               <div v-if="attachment.pinned" @click="pinAttachment(attachment)" class="btn btn-primary btn-xs btn-micro tooltip-more pointer-hand" :title=" __('un_pin_under_video') " :data-original-title=" __('un_pin_under_video') "><i class="fas fa-check-square" aria-hidden="true"></i> {{ __('pin') }}</div>
               <div v-else @click="pinAttachment(attachment)" class="btn btn-default btn-xs btn-micro tooltip-more pointer-hand" :title=" __('pin_under_video') " :data-original-title=" __('pin_under_video') "><i class="fas fa-check-square" aria-hidden="true"></i> {{ __('pin') }}</div>
          </span>
        </small
        ><strong>{{ attachment.authorable.name }}</strong
        ><span class="label label-default" v-if="attachment.label == 'Klient'"
          >{{ __('client') }}</span
        >
        <span class="label label-default" v-if="attachment.label == 'Bez konta'"
          >{{ __('no_account') }}</span
        >
        {{ __('added_file') }}:
      </div>
      <div class="well">
        <a :href="attachment.url"
          ><i class="fas fa-paperclip"></i> {{ attachment.name }} (.{{
            attachment.extension
          }})</a
        ><br />
        {{ attachment.description }}
        <br /><small
          >{{ __('added_in') }}:
          {{ attachment.addedIn }}
          <a :href="attachment.route">{{ attachment.href }}</a></small
        >
      </div>
    </div>
    <span v-if="attachments.length === 0">{{ __('no_attachments') }}</span>
  </div>
</template>

<script>
export default {
  name: "AttachmentsList",
  props: ["user", "propAttachments", "video"],
  data() {
    return {
      attachments: [],
    };
  },
  updated() {
    $(".tooltip-more").tooltip();
  },
  watch: {
    propAttachments: function (value, oldValue) {
      this.attachments = value;
      this.$eventBus.$emit("getAttachemts", this.attachments);
    },
  },
  methods: {
    pinAttachment(attachment) {
       let formData = new FormData();
       formData.append("id", attachment.id);
       axios.post("/api/attachment/pin", formData, {
          headers: { "content-type": "multipart/form-data" },
        })
         .then((response) => {
          attachment.pinned = response.data.pinned;
          this.$eventBus.$emit("getAttachemts", this.attachments);
        })
        .catch((error) => {
          this.loader = false;
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {};
          }
        }); 
    }
  }
};
</script>
