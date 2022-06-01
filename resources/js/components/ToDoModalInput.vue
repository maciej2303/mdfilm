<template>
  <div>
    <at v-model="content" :members="users" name-key="name">
      <div
        id="content-field"
        type="text"
        class="editor form-control"
        contenteditable
      ></div>
    </at>
    <input
      type="hidden"
      class="input-content"
      name="content"
      :value="content | strippedContent"
    />
  </div>
</template>

<script>
import At from "vue-at";
export default {
  components: { At },
  props: {
    users: { type: Array, default: [] },
  },
  data() {
    return {
      content: "",
    };
  },
  filters: {
    strippedContent: function (string) {
      return string.replace(/(?:&nbsp;|<br>)/g, "");
    },
  },
};
</script>
<style>
.atwho-panel {
  top: -2px !important;
}
</style>