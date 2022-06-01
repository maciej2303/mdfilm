<template>
  <div>
    <sweet-modal ref="modal" :title=" __('add_pin') " @close="closedModal"
      ><comment-form-component
        ref="commentForm"
        :prop-relationable-id="propRelationableId"
        :prop-relationable-type="propRelationableType"
        :prop-inner="0"
        :show-inside-pin="true"
        :youtube-video="true"
        :modal="true"
      ></comment-form-component
    ></sweet-modal>
  </div>
</template>

<script>
import { SweetModal, SweetModalTab } from "sweet-modal-vue";
import CommentFormComponent from "./comments/CommentFormComponent.vue";
export default {
  components: { SweetModal, SweetModalTab, CommentFormComponent },
  props: [
    "propRelationableId",
    "propRelationableType",
    "propInner",
    "showInsidePin"
  ],
  mounted() {
    this.$eventBus.$on("openMarkerModal", this.openModal);
  },

  methods: {
    open() {
      this.$refs.modal.open();
    },
    close() {
      this.$refs.modal.close();
    },
    openModal(data) {
      this.$refs.modal.open();
      this.$refs.commentForm.passData(data);
    },
    closedModal() {
      setTimeout(() => {
        this.$eventBus.$emit("modalClosed");
      }, 500);
    },
  },
};
</script>
<style>
.sweet-modal .sweet-title > h2 {
  line-height: 64px;
}
.sweet-modal .sweet-title {
  margin-right: 64px;
}
.sweet-modal-overlay {
  background: rgb(0, 0, 0, 0.6);
}
.swal2-container {
  z-index: 9999;
}
</style>