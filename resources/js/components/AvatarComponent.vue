<template>
  <avatar
    class="rounded-circle tooltip-more"
    :username="user.name"
    :initials="initials(user.name)"
    :src="asset(user.avatar)"
    :size="30"
    :title="user.name"
    backgroundColor="gray"
    color="white"
  >
  </avatar>
</template>
<script>
import Avatar from "vue-avatar";
export default {
  components: {
    Avatar,
  },
  props: ["user"],
  methods: {
    initials(name) {
      return name.substring(0, 2).toUpperCase();
    },
    asset(path) {
      let imagePath = null;
      if (path != null) {
        let base_path = window._asset || window.location.origin + "/";
        imagePath = base_path + path;
      }
      return imagePath;
    },
  },
};
</script>
