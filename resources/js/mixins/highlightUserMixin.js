export default {
  methods: {
    highlightUser(text) {
      this.users.forEach((element) => {
        let user = "@" + element.name;
        if (text.includes(user)) {
          text = text.replaceAll(
            user,
            '<span class="callPerson">' + "@" + element.name + "</span>"
          );
        }
      });
      return text;
    },
  }
};