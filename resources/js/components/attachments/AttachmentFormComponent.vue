<template>
  <div class="m-b">
    <div class="input mt-3 m-b-xs" v-if="toggle">
      <form
        @submit.prevent="storeAttachment"
        v-bind:class="{ 'd-none': loader }"
      >
        <label>{{ __('attachment') }}*</label>
        <div class="custom-file m-b-xs">
          <input
            type="file"
            class="custom-file-input form-control"
            @change="fileSelected"
          />
          <label class="custom-file-label" v-if="file != null">{{
            file.name
          }}</label>
          <label
            class="custom-file-label"
            :style="[
              errors && errors.file
                ? { 'border-color': '#ED5565' }
                : { 'border-color': '#e5e6e7' },
            ]"
            v-else
            >{{ __('chose_file') }}</label
          >
          <small v-if="errors && errors.file" class="text-danger">{{
            errors.file[0]
          }}</small>
        </div>
        <div :class="{ 'has-error': errors && errors.name }">
          <label>{{ __('name') }}*</label>
          <input type="text" class="form-control" v-model="form.name" />
          <small v-if="errors && errors.name" class="text-danger">{{
            errors.name[0]
          }}</small>
        </div>
        <div :class="{ 'has-error': errors && errors.description }">
          <label>{{ __('description') }}</label>
          <textarea
            type="text"
            class="form-control"
            v-model="form.description"
          ></textarea>
          <small v-if="errors && errors.description" class="text-danger">{{
            errors.description[0]
          }}</small>
        </div>
        <button class="btn btn-sm btn-primary mt-2" id="save-file">
          {{ __('save') }}
        </button>
      </form>
    </div>
    <moon-loader class="mb-2" v-if="loader"></moon-loader>
    <button class="btn btn-xs btn-primary m-t-xs" @click="toggle = !toggle">
       {{ __('add_attachment') }}
    </button>
  </div>
</template>

<script>
import MoonLoader from "vue-spinner/src/MoonLoader.vue";
export default {
  components: {
    MoonLoader,
  },
  props: ["propRelationableId", "propRelationableType", "userId"],
  data() {
    return {
      errors: {},
      valid: true,
      toggle: false,
      file: null,
      loader: false,
      form: {
        name: "",
        description: "",
        relationable_id: "",
        relationable_type: "",
      },
    };
  },
  mounted() {
    this.form.relationable_id = this.propRelationableId;
    this.form.relationable_type = this.propRelationableType;
    this.form.userId = this.userId;
  },
  methods: {
    fileSelected(e) {
      this.file = e.target.files[0];
    },
    storeAttachment() {
      let formData = new FormData();
      if (this.file != null) {
        formData.append("file", this.file);
      }
      formData.append("name", this.form.name);
      formData.append("description", this.form.description);
      formData.append("relationable_id", this.form.relationable_id);
      formData.append("relationable_type", this.form.relationable_type);
      formData.append("userId", this.userId);
      this.loader = true;
      axios
        .post("/api/attachment/store", formData, {
          headers: { "content-type": "multipart/form-data" },
        })
        .then((response) => {
          this.loader = false;
          this.errors = null;
          this.toggle = false;
          this.file = null;
          this.form.description = "";
          this.form.name = "";
          this.$emit("attachment", response.data.attachment);
          this.$eventBus.$emit("getAttachemts", this.attachments);
        })
        .catch((error) => {
          this.loader = false;
          if (error.response.status === 422) {
            this.errors = error.response.data.errors || {};
          }
        });
    },
  },
};
</script>
